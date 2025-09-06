<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call - USGamNeeds</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #1a1a1a;
            color: white;
            height: 100vh;
            overflow: hidden;
        }

        .video-container {
            display: flex;
            height: 100vh;
            flex-direction: column;
        }

        .video-header {
            background: rgba(0,0,0,0.8);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .call-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .call-title {
            font-size: 18px;
            font-weight: 600;
        }

        .participants-count {
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }

        .call-controls {
            display: flex;
            gap: 10px;
        }

        .control-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.2s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .control-btn.active {
            background: #4ade80;
        }

        .control-btn.danger {
            background: #ef4444;
        }

        .control-btn.danger:hover {
            background: #dc2626;
        }

        .video-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 10px;
            padding: 20px;
            overflow: auto;
        }

        .video-participant {
            background: #2a2a2a;
            border-radius: 12px;
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .video-element {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .participant-info {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0,0,0,0.7);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
        }

        .participant-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
        }

        .participant-control {
            background: rgba(0,0,0,0.7);
            border: none;
            color: white;
            padding: 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 10px;
        }

        .video-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: #333;
        }

        .avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .bottom-controls {
            background: rgba(0,0,0,0.8);
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            backdrop-filter: blur(10px);
        }

        .main-control-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .main-control-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }

        .main-control-btn.active {
            background: #4ade80;
        }

        .main-control-btn.danger {
            background: #ef4444;
        }

        .main-control-btn.danger:hover {
            background: #dc2626;
        }

        .screen-share {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
            z-index: 1000;
            display: none;
        }

        .screen-share.active {
            display: block;
        }

        .screen-share-controls {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            padding: 10px 20px;
            border-radius: 25px;
            display: flex;
            gap: 10px;
        }

        .chat-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background: #1a1a1a;
            border-left: 1px solid #333;
            transition: right 0.3s;
            z-index: 100;
        }

        .chat-sidebar.open {
            right: 0;
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            height: calc(100vh - 140px);
            overflow-y: auto;
            padding: 20px;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .message-content {
            flex: 1;
        }

        .message-sender {
            font-size: 12px;
            color: #888;
            margin-bottom: 2px;
        }

        .message-text {
            background: #333;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .chat-input {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: #1a1a1a;
            border-top: 1px solid #333;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .message-input {
            flex: 1;
            background: #333;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .message-input:focus {
            outline: none;
        }

        .send-btn {
            background: #667eea;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }

        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            flex-direction: column;
            gap: 20px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #333;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4ade80;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 1001;
            transform: translateX(400px);
            transition: transform 0.3s;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>
</head>
<body>
    <div class="video-container">
        <!-- Header -->
        <div class="video-header">
            <div class="call-info">
                <div class="call-title">Team Meeting</div>
                <div class="participants-count" id="participantsCount">1 participant</div>
            </div>
            <div class="call-controls">
                <button class="control-btn" onclick="toggleChat()" title="Chat">💬</button>
                <button class="control-btn" onclick="toggleParticipants()" title="Participants">👥</button>
                <button class="control-btn" onclick="toggleSettings()" title="Settings">⚙️</button>
            </div>
        </div>

        <!-- Video Grid -->
        <div class="video-grid" id="videoGrid">
            <div class="video-participant" id="localVideo">
                <div class="video-placeholder">
                    <div class="avatar-large">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div>You</div>
                </div>
                <div class="participant-info">You</div>
                <div class="participant-controls">
                    <button class="participant-control" onclick="toggleMute()">🔇</button>
                    <button class="participant-control" onclick="toggleVideo()">📹</button>
                </div>
            </div>
        </div>

        <!-- Bottom Controls -->
        <div class="bottom-controls">
            <button class="main-control-btn" id="muteBtn" onclick="toggleMute()" title="Mute">🎤</button>
            <button class="main-control-btn" id="videoBtn" onclick="toggleVideo()" title="Camera">📹</button>
            <button class="main-control-btn" id="screenBtn" onclick="toggleScreenShare()" title="Share Screen">🖥️</button>
            <button class="main-control-btn" id="chatBtn" onclick="toggleChat()" title="Chat">💬</button>
            <button class="main-control-btn danger" onclick="endCall()" title="End Call">📞</button>
        </div>
    </div>

    <!-- Screen Share Overlay -->
    <div class="screen-share" id="screenShare">
        <div class="screen-share-controls">
            <button class="control-btn" onclick="toggleScreenShare()">Stop Sharing</button>
        </div>
        <video id="screenVideo" autoplay muted style="width: 100%; height: 100%; object-fit: contain;"></video>
    </div>

    <!-- Chat Sidebar -->
    <div class="chat-sidebar" id="chatSidebar">
        <div class="chat-header">
            <h3>Meeting Chat</h3>
            <button class="control-btn" onclick="toggleChat()">✕</button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message">
                <div class="message-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="message-content">
                    <div class="message-sender">You</div>
                    <div class="message-text">Welcome to the meeting!</div>
                </div>
            </div>
        </div>
        <div class="chat-input">
            <div class="input-group">
                <input type="text" class="message-input" id="chatInput" placeholder="Type a message...">
                <button class="send-btn" onclick="sendChatMessage()">Send</button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        let localStream = null;
        let isMuted = false;
        let isVideoOn = true;
        let isScreenSharing = false;
        let participants = [];

        // Initialize video call
        document.addEventListener('DOMContentLoaded', function() {
            initializeVideoCall();
        });

        async function initializeVideoCall() {
            try {
                // Request camera and microphone access
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });

                // Display local video
                const localVideoElement = document.createElement('video');
                localVideoElement.srcObject = localStream;
                localVideoElement.autoplay = true;
                localVideoElement.muted = true;
                localVideoElement.className = 'video-element';

                const localVideoContainer = document.getElementById('localVideo');
                localVideoContainer.querySelector('.video-placeholder').style.display = 'none';
                localVideoContainer.insertBefore(localVideoElement, localVideoContainer.firstChild);

                showNotification('Video call started successfully!');
            } catch (error) {
                console.error('Error accessing media devices:', error);
                showNotification('Could not access camera/microphone. Please check permissions.');
            }
        }

        function toggleMute() {
            if (localStream) {
                const audioTracks = localStream.getAudioTracks();
                audioTracks.forEach(track => {
                    track.enabled = !track.enabled;
                });
                isMuted = !isMuted;
                
                const muteBtn = document.getElementById('muteBtn');
                muteBtn.textContent = isMuted ? '🎤' : '🔇';
                muteBtn.classList.toggle('active', isMuted);
                
                showNotification(isMuted ? 'Microphone muted' : 'Microphone unmuted');
            }
        }

        function toggleVideo() {
            if (localStream) {
                const videoTracks = localStream.getVideoTracks();
                videoTracks.forEach(track => {
                    track.enabled = !track.enabled;
                });
                isVideoOn = !isVideoOn;
                
                const videoBtn = document.getElementById('videoBtn');
                videoBtn.textContent = isVideoOn ? '📹' : '📷';
                videoBtn.classList.toggle('active', !isVideoOn);
                
                showNotification(isVideoOn ? 'Camera on' : 'Camera off');
            }
        }

        async function toggleScreenShare() {
            try {
                if (!isScreenSharing) {
                    const screenStream = await navigator.mediaDevices.getDisplayMedia({
                        video: true,
                        audio: true
                    });

                    const screenVideo = document.getElementById('screenVideo');
                    screenVideo.srcObject = screenStream;
                    
                    document.getElementById('screenShare').classList.add('active');
                    document.getElementById('screenBtn').classList.add('active');
                    isScreenSharing = true;

                    showNotification('Screen sharing started');

                    // Stop screen sharing when user clicks stop
                    screenStream.getVideoTracks()[0].onended = function() {
                        toggleScreenShare();
                    };
                } else {
                    document.getElementById('screenShare').classList.remove('active');
                    document.getElementById('screenBtn').classList.remove('active');
                    isScreenSharing = false;
                    showNotification('Screen sharing stopped');
                }
            } catch (error) {
                console.error('Error sharing screen:', error);
                showNotification('Could not start screen sharing');
            }
        }

        function toggleChat() {
            const chatSidebar = document.getElementById('chatSidebar');
            chatSidebar.classList.toggle('open');
        }

        function toggleParticipants() {
            showNotification('Participants panel coming soon!');
        }

        function toggleSettings() {
            showNotification('Settings panel coming soon!');
        }

        function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            
            if (message) {
                addChatMessage('You', message);
                input.value = '';
                
                // Simulate other participants responding
                setTimeout(() => {
                    const responses = [
                        'Thanks for sharing!',
                        'Great point!',
                        'I agree with that',
                        'Let me check on that',
                        'Sounds good to me'
                    ];
                    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                    addChatMessage('Team Member', randomResponse);
                }, 1000 + Math.random() * 2000);
            }
        }

        function addChatMessage(sender, message) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message';
            
            const isOwn = sender === 'You';
            const avatar = isOwn ? '{{ substr(auth()->user()->name, 0, 1) }}' : sender.charAt(0);
            
            messageDiv.innerHTML = `
                <div class="message-avatar">${avatar}</div>
                <div class="message-content">
                    <div class="message-sender">${sender}</div>
                    <div class="message-text">${message}</div>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function endCall() {
            if (confirm('Are you sure you want to end the call?')) {
                if (localStream) {
                    localStream.getTracks().forEach(track => track.stop());
                }
                showNotification('Call ended');
                setTimeout(() => {
                    window.close();
                }, 1000);
            }
        }

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Simulate participants joining
        setTimeout(() => {
            addParticipant('John Doe');
        }, 3000);

        setTimeout(() => {
            addParticipant('Jane Smith');
        }, 5000);

        function addParticipant(name) {
            const videoGrid = document.getElementById('videoGrid');
            const participantDiv = document.createElement('div');
            participantDiv.className = 'video-participant';
            participantDiv.innerHTML = `
                <div class="video-placeholder">
                    <div class="avatar-large">${name.charAt(0)}</div>
                    <div>${name}</div>
                </div>
                <div class="participant-info">${name}</div>
                <div class="participant-controls">
                    <button class="participant-control">🔇</button>
                    <button class="participant-control">📹</button>
                </div>
            `;
            
            videoGrid.appendChild(participantDiv);
            participants.push(name);
            updateParticipantsCount();
            
            addChatMessage('System', `${name} joined the meeting`);
        }

        function updateParticipantsCount() {
            const count = participants.length + 1; // +1 for local user
            document.getElementById('participantsCount').textContent = `${count} participant${count > 1 ? 's' : ''}`;
        }

        // Handle Enter key in chat input
        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });
    </script>
</body>
</html>
