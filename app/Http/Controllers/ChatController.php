<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\Mention;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get all chat rooms for the authenticated user.
     */
    public function index()
    {
        $user = auth()->user();
        
        $rooms = $user->chatRooms()
            ->with(['latestMessage.user', 'users'])
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('created_at', '>', function ($subQuery) use ($user) {
                    $subQuery->select('last_read_at')
                        ->from('chat_room_user')
                        ->where('room_id', DB::raw('chat_rooms.id'))
                        ->where('user_id', $user->id);
                });
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($rooms);
    }

    /**
     * Create a new chat room.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:general,project,task,direct',
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'is_private' => 'boolean',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room = ChatRoom::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'created_by' => auth()->id(),
            'is_private' => $request->is_private ?? false,
        ]);

        // Add creator as admin
        $room->users()->attach(auth()->id(), [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        // Add other users
        foreach ($request->user_ids as $userId) {
            if ($userId != auth()->id()) {
                $room->users()->attach($userId, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
            }
        }

        $room->load(['users', 'creator']);

        return response()->json($room, 201);
    }

    /**
     * Get a specific chat room with messages.
     */
    public function show(ChatRoom $room)
    {
        $user = auth()->user();
        
        if (!$room->hasUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        $room->users()->updateExistingPivot($user->id, [
            'last_read_at' => now()
        ]);

        $room->load(['users', 'creator', 'project', 'task']);
        
        $messages = $room->messages()
            ->with(['user', 'mentions.mentionedUser', 'replies.user'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'room' => $room,
            'messages' => $messages
        ]);
    }

    /**
     * Send a message to a chat room.
     */
    public function sendMessage(Request $request, ChatRoom $room)
    {
        $user = auth()->user();
        
        if (!$room->hasUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'type' => 'in:text,file,image,system,meeting',
            'parent_id' => 'nullable|exists:chat_messages,id',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
            'metadata' => $request->metadata
        ]);

        // Process mentions
        $this->processMentions($message, $request->message);

        $message->load(['user', 'mentions.mentionedUser', 'parent']);

        return response()->json($message, 201);
    }

    /**
     * Get messages for a chat room with pagination.
     */
    public function getMessages(ChatRoom $room, Request $request)
    {
        $user = auth()->user();
        
        if (!$room->hasUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $perPage = $request->get('per_page', 50);
        $before = $request->get('before');

        $query = $room->messages()
            ->with(['user', 'mentions.mentionedUser', 'replies.user'])
            ->orderBy('created_at', 'desc');

        if ($before) {
            $query->where('created_at', '<', $before);
        }

        $messages = $query->paginate($perPage);

        return response()->json($messages);
    }

    /**
     * Add users to a chat room.
     */
    public function addUsers(Request $request, ChatRoom $room)
    {
        $user = auth()->user();
        
        if (!$room->isAdmin($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->user_ids as $userId) {
            if (!$room->hasUser(User::find($userId))) {
                $room->users()->attach($userId, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
            }
        }

        return response()->json(['message' => 'Users added successfully']);
    }

    /**
     * Remove users from a chat room.
     */
    public function removeUsers(Request $request, ChatRoom $room)
    {
        $user = auth()->user();
        
        if (!$room->isAdmin($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $room->users()->detach($request->user_ids);

        return response()->json(['message' => 'Users removed successfully']);
    }

    /**
     * Leave a chat room.
     */
    public function leave(ChatRoom $room)
    {
        $user = auth()->user();
        
        if (!$room->hasUser($user)) {
            return response()->json(['error' => 'Not a member'], 400);
        }

        $room->users()->detach($user->id);

        return response()->json(['message' => 'Left room successfully']);
    }

    /**
     * Get unread mentions for the authenticated user.
     */
    public function getUnreadMentions()
    {
        $user = auth()->user();
        
        $mentions = Mention::getUnreadForUser($user);

        return response()->json($mentions);
    }

    /**
     * Mark mentions as read.
     */
    public function markMentionsAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mention_ids' => 'required|array',
            'mention_ids.*' => 'exists:mentions,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Mention::whereIn('id', $request->mention_ids)
            ->where('mentioned_user_id', auth()->id())
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['message' => 'Mentions marked as read']);
    }

    /**
     * Process mentions in a message.
     */
    private function processMentions(ChatMessage $message, string $text)
    {
        preg_match_all('/@(\w+)/', $text, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $username) {
                $user = User::where('name', 'LIKE', "%{$username}%")->first();
                
                if ($user && $user->id !== $message->user_id) {
                    Mention::create([
                        'message_id' => $message->id,
                        'mentioned_user_id' => $user->id,
                        'mentioned_by' => $message->user_id
                    ]);
                }
            }
        }
    }

    /**
     * Get users for mention suggestions.
     */
    public function getMentionSuggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}