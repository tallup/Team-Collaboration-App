<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Chat - USGamNeeds</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .chat-container {
            display: flex;
            height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Sidebar */
        .chat-sidebar {
            width: 300px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            background: white;
        }

        .sidebar-header h2 {
            color: #1a202c;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .create-room-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .create-room-btn:hover {
            background: #5a67d8;
        }

        .rooms-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .room-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            margin-bottom: 4px;
        }

        .room-item:hover {
            background: #e2e8f0;
        }

        .room-item.active {
            background: #667eea;
            color: white;
        }

        .room-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
        }

        .room-info {
            flex: 1;
        }

        .room-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .room-last-message {
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .room-item.active .room-last-message {
            color: rgba(255,255,255,0.8);
        }

        .unread-badge {
            background: #ef4444;
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
        }

        /* Main Chat Area */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
        }

        .chat-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            background: #f1f5f9;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .action-btn:hover {
            background: #e2e8f0;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8fafc;
        }

        .message {
            display: flex;
            margin-bottom: 16px;
            align-items: flex-start;
        }

        .message.own {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            margin: 0 8px;
        }

        .message-content {
            max-width: 70%;
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .message.own .message-content {
            background: #667eea;
            color: white;
        }

        .message-header {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }

        .message-sender {
            font-weight: 600;
            font-size: 12px;
            margin-right: 8px;
        }

        .message-time {
            font-size: 11px;
            color: #64748b;
        }

        .message.own .message-time {
            color: rgba(255,255,255,0.7);
        }

        .message-text {
            font-size: 14px;
            line-height: 1.4;
        }

        .message.own .message-text {
            color: white;
        }

        .mention {
            background: #fef3c7;
            color: #92400e;
            padding: 1px 4px;
            border-radius: 3px;
            font-weight: 600;
            cursor: pointer;
        }

        .message.own .mention {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Input Area */
        .chat-input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #e2e8f0;
        }

        .input-container {
            display: flex;
            align-items: flex-end;
            gap: 12px;
        }

        .message-input {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            resize: none;
            min-height: 44px;
            max-height: 120px;
            font-family: inherit;
        }

        .message-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .send-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }

        .send-btn:hover {
            background: #5a67d8;
        }

        .send-btn:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
        }

        .file-upload-btn {
            background: #f1f5f9;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .file-upload-btn:hover {
            background: #e2e8f0;
        }

        /* File Message */
        .file-message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .file-icon {
            width: 24px;
            height: 24px;
            background: #667eea;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }

        .file-details {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .file-size {
            font-size: 11px;
            color: #64748b;
        }

        /* Threaded Messages */
        .thread-reply {
            margin-left: 40px;
            border-left: 2px solid #e2e8f0;
            padding-left: 12px;
        }

        .reply-btn {
            background: none;
            border: none;
            color: #667eea;
            font-size: 12px;
            cursor: pointer;
            padding: 4px 0;
        }

        .reply-btn:hover {
            text-decoration: underline;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #64748b;
            text-align: center;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #1a202c;
        }

        .empty-state p {
            font-size: 14px;
            max-width: 300px;
        }

        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #64748b;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                border-radius: 0;
            }
            
            .chat-sidebar {
                width: 100%;
                position: absolute;
                z-index: 10;
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            
            .chat-sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar" id="chatSidebar">
            <div class="sidebar-header">
                <h2>Team Chat</h2>
                <button class="create-room-btn" onclick="openCreateRoomModal()">
                    + New Room
                </button>
            </div>
            
            <div class="rooms-list" id="roomsList">
                <div class="loading">
                    <div class="spinner"></div>
                    Loading rooms...
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="chat-main">
            <div class="chat-header">
                <div class="chat-title" id="chatTitle">Select a room to start chatting</div>
                <div class="chat-actions">
                    <button class="action-btn" onclick="toggleSidebar()">☰</button>
                    <button class="action-btn" onclick="startVideoCall()">📹</button>
                    <button class="action-btn" onclick="shareScreen()">🖥️</button>
                </div>
            </div>

            <div class="messages-container" id="messagesContainer">
                <div class="empty-state">
                    <h3>Welcome to Team Chat</h3>
                    <p>Select a room from the sidebar to start chatting with your team members.</p>
                </div>
            </div>

            <div class="chat-input-area" id="chatInputArea" style="display: none;">
                <div class="input-container">
                    <button class="file-upload-btn" onclick="uploadFile()" title="Upload file">
                        📎
                    </button>
                    <textarea 
                        class="message-input" 
                        id="messageInput" 
                        placeholder="Type a message..."
                        rows="1"
                    ></textarea>
                    <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden file input -->
    <input type="file" id="fileInput" style="display: none;" multiple>

    <script>
        let currentRoom = null;
        let messages = [];
        let users = [];

        // Initialize chat
        document.addEventListener('DOMContentLoaded', function() {
            loadRooms();
            setupEventListeners();
        });

        function setupEventListeners() {
            const messageInput = document.getElementById('messageInput');
            
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });

            // File input change
            document.getElementById('fileInput').addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    uploadFiles(e.target.files);
                }
            });
        }

        async function loadRooms() {
            try {
                const response = await fetch('/api/chat/rooms');
                const rooms = await response.json();
                renderRooms(rooms);
            } catch (error) {
                console.error('Error loading rooms:', error);
            }
        }

        function renderRooms(rooms) {
            const roomsList = document.getElementById('roomsList');
            
            if (rooms.length === 0) {
                roomsList.innerHTML = `
                    <div class="empty-state">
                        <h3>No rooms yet</h3>
                        <p>Create your first room to start chatting!</p>
                    </div>
                `;
                return;
            }

            roomsList.innerHTML = rooms.map(room => `
                <div class="room-item" onclick="selectRoom(${room.id})" data-room-id="${room.id}">
                    <div class="room-avatar">
                        ${room.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="room-info">
                        <div class="room-name">${room.name}</div>
                        <div class="room-last-message">
                            ${room.latest_message ? room.latest_message.message : 'No messages yet'}
                        </div>
                    </div>
                    ${room.unread_count > 0 ? `<div class="unread-badge">${room.unread_count}</div>` : ''}
                </div>
            `).join('');
        }

        async function selectRoom(roomId) {
            // Update active room
            document.querySelectorAll('.room-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-room-id="${roomId}"]`).classList.add('active');

            // Load room data
            try {
                const response = await fetch(`/api/chat/rooms/${roomId}`);
                const data = await response.json();
                
                currentRoom = data.room;
                messages = data.messages;
                
                document.getElementById('chatTitle').textContent = currentRoom.name;
                document.getElementById('chatInputArea').style.display = 'block';
                
                renderMessages();
            } catch (error) {
                console.error('Error loading room:', error);
            }
        }

        function renderMessages() {
            const container = document.getElementById('messagesContainer');
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <h3>No messages yet</h3>
                        <p>Be the first to send a message in this room!</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = messages.map(message => {
                const isOwn = message.user_id === {{ auth()->id() }};
                const time = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                return `
                    <div class="message ${isOwn ? 'own' : ''}" data-message-id="${message.id}">
                        <div class="message-avatar">
                            ${message.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <span class="message-sender">${message.user.name}</span>
                                <span class="message-time">${time}</span>
                            </div>
                            <div class="message-text">${formatMessage(message.message)}</div>
                            ${message.replies && message.replies.length > 0 ? `
                                <div class="thread-replies">
                                    ${message.replies.map(reply => renderMessage(reply, true)).join('')}
                                </div>
                            ` : ''}
                            <button class="reply-btn" onclick="replyToMessage(${message.id})">
                                Reply
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        function formatMessage(text) {
            // Convert @mentions to clickable elements
            return text.replace(/@(\w+)/g, '<span class="mention" data-user="$1">@$1</span>');
        }

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || !currentRoom) return;

            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;
            sendBtn.textContent = 'Sending...';

            try {
                const response = await fetch(`/api/chat/rooms/${currentRoom.id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message,
                        type: 'text'
                    })
                });

                if (response.ok) {
                    input.value = '';
                    input.style.height = 'auto';
                    loadRoomMessages();
                }
            } catch (error) {
                console.error('Error sending message:', error);
            } finally {
                sendBtn.disabled = false;
                sendBtn.textContent = 'Send';
            }
        }

        async function loadRoomMessages() {
            if (!currentRoom) return;

            try {
                const response = await fetch(`/api/chat/rooms/${currentRoom.id}/messages`);
                const data = await response.json();
                messages = data.data;
                renderMessages();
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        function uploadFile() {
            document.getElementById('fileInput').click();
        }

        async function uploadFiles(files) {
            for (let file of files) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('room_id', currentRoom.id);

                try {
                    const response = await fetch('/api/files/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    if (response.ok) {
                        // Send file message
                        const fileData = await response.json();
                        await sendFileMessage(fileData);
                    }
                } catch (error) {
                    console.error('Error uploading file:', error);
                }
            }
        }

        async function sendFileMessage(fileData) {
            try {
                const response = await fetch(`/api/chat/rooms/${currentRoom.id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: `📎 ${fileData.original_filename}`,
                        type: 'file',
                        metadata: {
                            file_id: fileData.id,
                            filename: fileData.original_filename,
                            file_size: fileData.file_size
                        }
                    })
                });

                if (response.ok) {
                    loadRoomMessages();
                }
            } catch (error) {
                console.error('Error sending file message:', error);
            }
        }

        function replyToMessage(messageId) {
            const message = messages.find(m => m.id === messageId);
            if (message) {
                const input = document.getElementById('messageInput');
                input.value = `@${message.user.name} `;
                input.focus();
            }
        }

        function openCreateRoomModal() {
            // TODO: Implement create room modal
            alert('Create room functionality coming soon!');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('chatSidebar');
            sidebar.classList.toggle('open');
        }

        function startVideoCall() {
            window.open('/admin/video-call', '_blank', 'width=1200,height=800');
        }

        function shareScreen() {
            // TODO: Implement screen sharing
            alert('Screen sharing functionality coming soon!');
        }

        // Auto-refresh messages every 5 seconds
        setInterval(() => {
            if (currentRoom) {
                loadRoomMessages();
            }
        }, 5000);
    </script>
</body>
</html>
