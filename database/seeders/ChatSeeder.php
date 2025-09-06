<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->count() < 2) {
            $this->command->info('Need at least 2 users to create chat rooms. Creating sample users...');
            return;
        }

        // Create general team chat room
        $generalRoom = ChatRoom::create([
            'name' => 'General Team Chat',
            'description' => 'Main team communication channel',
            'type' => 'general',
            'created_by' => $users->first()->id,
            'is_private' => false,
        ]);

        // Add all users to general room
        foreach ($users as $user) {
            $generalRoom->users()->attach($user->id, [
                'role' => $user->id === $users->first()->id ? 'admin' : 'member',
                'joined_at' => now()
            ]);
        }

        // Create project-specific rooms
        $projects = Project::all();
        foreach ($projects->take(2) as $project) {
            $projectRoom = ChatRoom::create([
                'name' => $project->name . ' Discussion',
                'description' => 'Project-specific discussions for ' . $project->name,
                'type' => 'project',
                'project_id' => $project->id,
                'created_by' => $users->first()->id,
                'is_private' => false,
            ]);

            // Add project team members
            $projectUsers = $project->users;
            if ($projectUsers->count() === 0) {
                $projectUsers = $users->take(3);
            }

            foreach ($projectUsers as $user) {
                $projectRoom->users()->attach($user->id, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
            }
        }

        // Create some sample messages
        $this->createSampleMessages($generalRoom, $users);
        
        // Create messages for project rooms
        foreach ($projects->take(2) as $project) {
            $projectRoom = ChatRoom::where('project_id', $project->id)->first();
            if ($projectRoom) {
                $this->createSampleMessages($projectRoom, $users->take(3));
            }
        }

        $this->command->info('Chat rooms and messages created successfully!');
    }

    private function createSampleMessages(ChatRoom $room, $users)
    {
        $sampleMessages = [
            'Welcome to the team! 🎉',
            'Let\'s discuss the project timeline',
            'Has anyone reviewed the latest changes?',
            'Great work on the new feature!',
            'Can we schedule a meeting for tomorrow?',
            'The deadline is approaching, let\'s focus on the priorities',
            'I\'ve uploaded the design files to the shared folder',
            'Thanks for the feedback, I\'ll implement the changes',
            'Is everyone available for the demo on Friday?',
            'The client is happy with the progress so far'
        ];

        foreach ($sampleMessages as $index => $message) {
            $user = $users->random();
            
            ChatMessage::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'message' => $message,
                'type' => 'text',
                'created_at' => now()->subHours(rand(1, 48)),
                'updated_at' => now()->subHours(rand(1, 48)),
            ]);
        }

        // Create some messages with mentions
        $mentionMessages = [
            '@' . $users->first()->name . ' can you review this?',
            'Hey @' . $users->skip(1)->first()->name . ' when will you be available?',
            'Thanks @' . $users->last()->name . ' for the great work!'
        ];

        foreach ($mentionMessages as $message) {
            $user = $users->random();
            
            $chatMessage = ChatMessage::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'message' => $message,
                'type' => 'text',
                'created_at' => now()->subHours(rand(1, 24)),
                'updated_at' => now()->subHours(rand(1, 24)),
            ]);

            // Create mentions
            preg_match_all('/@(\w+)/', $message, $matches);
            foreach ($matches[1] as $username) {
                $mentionedUser = $users->where('name', 'LIKE', "%{$username}%")->first();
                if ($mentionedUser && $mentionedUser->id !== $user->id) {
                    \App\Models\Mention::create([
                        'message_id' => $chatMessage->id,
                        'mentioned_user_id' => $mentionedUser->id,
                        'mentioned_by' => $user->id,
                    ]);
                }
            }
        }
    }
}