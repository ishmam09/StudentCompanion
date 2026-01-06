@extends('layout.app')

@section('content')
    <style>
        /* UI Component Styles */
        .control-btn {
            width: 70px;
            height: 70px;
            /* Bigger buttons */
            border-radius: 16px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .control-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }

        .control-btn.active {
            transform: translateY(2px);
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.1);
            filter: brightness(0.9);
        }

        .main-container {
            max-width: 1600px;
            /* Centered and constrained */
            margin: 0 auto;
            height: 80vh;
            display: flex;
            align-items: stretch;
            gap: 1.5rem;
            position: relative;
        }

        .panel-container {
            border-radius: 20px;
            background: white;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        /* Green Panel (Posts) */
        .panel-posts {
            border: 2px solid #22c55e;
            width: 0;
            opacity: 0;
            transform: translateX(-20px);
        }

        .panel-posts.open {
            width: 400px;
            /* Bigger panel */
            opacity: 1;
            transform: translateX(0);
        }

        /* Blue Panel (Video) */
        .panel-video {
            border: 2px solid #3b82f6;
            flex: 1;
            position: relative;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Yellow Panel (Chat) */
        .panel-chat {
            border: 2px solid #eab308;
            width: 0;
            opacity: 0;
            transform: translateX(20px);
        }

        .panel-chat.open {
            width: 400px;
            /* Bigger panel */
            opacity: 1;
            transform: translateX(0);
        }

        .scroll-area {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8fafc;
        }

        /* Icons */
        .icon {
            width: 32px;
            height: 32px;
        }
    </style>

    <div style="padding: 2rem;">
        <!-- centralized header controls -->
        <div style="display: flex; justify-content: center; margin-bottom: 2rem;">
            <div
                style="background: white; padding: 0.75rem; border-radius: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); display: flex; gap: 1.5rem; align-items: center;">
                <button class="control-btn" id="btn-posts"
                    style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);" title="Announcements">
                    <!-- Bullhorn Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </button>

                <button class="control-btn" id="btn-video"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);" title="Video Stream">
                    <!-- Video Camera Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </button>

                @if(auth()->user()->role === 'student')
                    <button class="control-btn" id="btn-chat"
                        style="background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);" title="Chat">
                        <!-- Chat Bubble Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </button>
                @endif

                <div style="width: 1px; height: 40px; background: #cbd5e1; margin: 0 1rem;"></div>

                <!-- Class Info in Header -->
                <div style="display: flex; flex-direction: column; padding-right: 1rem;">
                    <span
                        style="font-size: 0.8rem; font-weight: 700; color: #64748b; letter-spacing: 1px;">{{ $session->course_code }}</span>
                    <span style="font-weight: 800; color: #1e293b; font-size: 1.1rem;">{{ $session->classroom_name }}</span>
                </div>

                @if(auth()->user()->role === 'teacher')
                    <div style="background: #fffbeb; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid #fcd34d;">
                        <span style="display: block; font-size: 0.7rem; color: #92400e; font-weight: 700;">CODE</span>
                        <span
                            style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: #b45309;">{{ $session->class_code }}</span>
                    </div>
                @endif
            </div>
        </div>


        <!-- Main Content Row -->
        <div class="main-container">

            <!-- 🟩 Posts Panel -->
            <div class="panel-container panel-posts" id="panel-posts">
                <div
                    style="background: #22c55e; color: white; padding: 1.25rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; items-center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Announcements
                    </div>
                    <button onclick="togglePanel('posts')"
                        style="background:none; border:none; color:white; cursor:pointer; font-size: 1.5rem;">&times;</button>
                </div>

                <div class="scroll-area" id="posts-feed">
                    <div style="text-align: center; color: #94a3b8; font-size: 0.9rem; margin-top: 2rem;">Loading posts...
                    </div>
                </div>

                @if(auth()->user()->role === 'teacher')
                    <div style="padding: 1.5rem; background: white; border-top: 1px solid #e2e8f0;">
                        <form id="post-form" onsubmit="sendPost(event)">
                            <textarea id="post-input" rows="3" placeholder="Write an announcement..." required
                                style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem; margin-bottom: 0.75rem; resize: none; transition: border-color 0.2s;"></textarea>
                            <button type="submit"
                                style="background: #16a34a; color: white; border: none; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; width: 100%;">Post
                                Announcement</button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- 🟦 Video Panel -->
            <div class="panel-container panel-video" id="panel-video">
                <!-- Placeholder State (Blank) -->
                <div id="video-placeholder"
                    style="width: 100%; height: 100%; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                    <!-- Blank state as requested -->
                </div>

                <!-- Jitsi Container -->
                <div id="jitsi-container" style="height: 100%; width: 100%; display: none;"></div>
            </div>

            <!-- 🟨 Chat Panel (Students Only) -->
            <div class="panel-container panel-chat" id="panel-chat">
                <div
                    style="background: #eab308; color: white; padding: 1.25rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; items-center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px;" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Class Chat
                    </div>
                    <button onclick="togglePanel('chat')"
                        style="background:none; border:none; color:white; cursor:pointer; font-size: 1.5rem;">&times;</button>
                </div>

                <div class="scroll-area" id="chat-messages">
                    <div style="text-align: center; color: #94a3b8; font-size: 0.9rem; margin-top: 2rem;">Loading chat...
                    </div>
                </div>

                @if(auth()->user()->role === 'student')
                    <div style="padding: 1.5rem; background: white; border-top: 1px solid #e2e8f0;">
                        <form id="chat-form" style="display: flex; gap: 0.75rem;" onsubmit="sendMessage(event)">
                            <input type="text" id="chat-input" placeholder="Type a message..." required
                                style="flex: 1; padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem;">
                            <button type="submit"
                                style="background: #ca8a04; color: white; border: none; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer;">Send</button>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        // --- Global Config ---
        const classId = '{{ $session->id }}';
        const userId = '{{ auth()->id() }}';

        // --- Panel Toggling ---
        const panels = {
            posts: { el: document.getElementById('panel-posts'), btn: document.getElementById('btn-posts') },
            video: { el: document.getElementById('panel-video'), btn: document.getElementById('btn-video') },
            chat: { el: document.getElementById('panel-chat'), btn: document.getElementById('btn-chat') }
        };

        function togglePanel(name) {
            if (!panels[name] || !panels[name].el) return;

            // Special specific logic for Video:
            if (name === 'video') {
                const isVideoActive = panels.video.btn.classList.contains('active');
                if (isVideoActive) {
                    // Stop video
                    document.getElementById('jitsi-container').innerHTML = ''; // Destroy Jitsi
                    document.getElementById('jitsi-container').style.display = 'none';
                    document.getElementById('video-placeholder').style.display = 'block';
                    panels.video.btn.classList.remove('active');
                } else {
                    // Start video
                    document.getElementById('video-placeholder').style.display = 'none';
                    document.getElementById('jitsi-container').style.display = 'block';
                    startJitsi();
                    panels.video.btn.classList.add('active');
                }
                return; // Don't use standard toggle class for video container itself as it's always open in center
            }

            const p = panels[name];
            p.el.classList.toggle('open');
            p.btn.classList.toggle('active');

            // Fetch data if opening
            if (p.el.classList.contains('open')) {
                if (name === 'chat') fetchChat();
                if (name === 'posts') fetchPosts();
            }
        }

        // Event Listeners
        document.getElementById('btn-posts').addEventListener('click', () => togglePanel('posts'));
        document.getElementById('btn-video').addEventListener('click', () => togglePanel('video'));
        if (panels.chat.btn) {
            document.getElementById('btn-chat').addEventListener('click', () => togglePanel('chat'));
        }

        // --- Jitsi Video ---
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $session->room_id }}',
            parentNode: document.querySelector('#jitsi-container'),
            userInfo: { displayName: '{{ auth()->user()->name }}' },
            configOverwrite: { startWithAudioMuted: true, startWithVideoMuted: true },
            width: '100%',
            height: '100%'
        };

        function startJitsi() {
            const api = new JitsiMeetExternalAPI(domain, options);
        }

        // --- Networking ---
        async function fetchChat() {
            if (!panels.chat.el || !panels.chat.el.classList.contains('open')) return;
            try {
                const res = await fetch(`/classroom/${classId}/messages`);
                const msgs = await res.json();
                const div = document.getElementById('chat-messages');
                div.innerHTML = '';
                msgs.forEach(msg => {
                    const isMe = msg.user_id == userId;
                    div.innerHTML += `
                        <div style="display: flex; flex-direction: column; align-items: ${isMe ? 'flex-end' : 'flex-start'}; margin-bottom: 0.75rem;">
                             <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 2px;">${isMe ? 'You' : msg.user.name}</div>
                             <div style="background: ${isMe ? '#fef08a' : 'white'}; padding: 0.75rem 1rem; border-radius: 12px; border-bottom-${isMe ? 'right' : 'left'}-radius: 2px; border: 1px solid #e2e8f0; font-size: 0.9rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); max-width: 85%;">
                                ${msg.message}
                             </div>
                        </div>`;
                });
                div.scrollTop = div.scrollHeight;
            } catch (e) { console.error(e); }
        }

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chat-input');
            if (!input.value.trim()) return;
            try {
                await fetch('/classroom/message', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ virtual_classroom_id: classId, message: input.value })
                });
                input.value = '';
                fetchChat();
            } catch (e) { alert('Error sending message'); }
        }

        async function fetchPosts() {
            if (!panels.posts.el.classList.contains('open')) return;
            try {
                const res = await fetch(`/classroom/${classId}/posts`);
                const posts = await res.json();
                const div = document.getElementById('posts-feed');
                div.innerHTML = '';
                posts.forEach(post => {
                    div.innerHTML += `
                        <div style="background: white; border-left: 4px solid #22c55e; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">${post.user.name}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8; margin-left: auto;">${new Date(post.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                            <div style="color: #334155; line-height: 1.6;">${post.content}</div>
                        </div>`;
                });
            } catch (e) { console.error(e); }
        }

        async function sendPost(e) {
            e.preventDefault();
            const input = document.getElementById('post-input');
            if (!input.value.trim()) return;
            try {
                await fetch('/classroom/posts', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ virtual_classroom_id: classId, content: input.value })
                });
                input.value = '';
                fetchPosts();
            } catch (e) { alert('Error sending post'); }
        }

        // Polling
        setInterval(() => {
            fetchChat();
            fetchPosts();
        }, 3000);
    </script>
@endsection