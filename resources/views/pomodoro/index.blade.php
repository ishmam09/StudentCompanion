@extends('layout.app')

@section('content')
    <div class="container" style="max-width: 800px; margin: 0 auto; padding: 2rem; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: white; margin-bottom: 2rem;">Focus Timer</h1>

        <!-- Timer Container -->
        <div
            style="background: rgba(30, 30, 30, 0.6); backdrop-filter: blur(10px); padding: 3rem; border-radius: 24px; border: 1px solid rgba(255, 215, 0, 0.1); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); display: inline-block; width: 100%; max-width: 500px;">

            <!-- Mode Toggles -->
            <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 2.5rem;">
                <button onclick="setMode('focus')" id="btn-focus" class="mode-btn active">Focus</button>
                <button onclick="setMode('short')" id="btn-short" class="mode-btn">Short Break</button>
                <button onclick="setMode('long')" id="btn-long" class="mode-btn">Long Break</button>
            </div>

            <!-- Timer Display (Circular) -->
            <div style="position: relative; width: 300px; height: 300px; margin: 0 auto 2.5rem;">
                <svg class="progress-ring" width="300" height="300">
                    <circle class="progress-ring__circle-bg" stroke="rgba(255,255,255,0.1)" stroke-width="12"
                        fill="transparent" r="140" cx="150" cy="150" />
                    <circle class="progress-ring__circle" stroke="#FFD700" stroke-width="12" fill="transparent" r="140"
                        cx="150" cy="150" />
                </svg>
                <div
                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center;">
                    <div id="timer-display"
                        style="font-size: 4.5rem; font-weight: 700; color: white; font-family: 'Plus Jakarta Sans', monospace; line-height: 1;">
                        25:00</div>
                    <div id="timer-status"
                        style="font-size: 1rem; color: rgba(255,255,255,0.5); margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 2px;">
                        Ready to Focus</div>
                </div>
            </div>

            <!-- Controls -->
            <div style="display: flex; gap: 1.5rem; justify-content: center;">
                <button onclick="toggleTimer()" id="main-btn"
                    style="padding: 1rem 3rem; font-size: 1.25rem; font-weight: 700; background: #FFD700; color: black; border: none; border-radius: 50px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                    Start
                </button>
                <button onclick="resetTimer()"
                    style="padding: 1rem; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; cursor: pointer; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <style>
        .mode-btn {
            padding: 0.5rem 1.25rem;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .mode-btn:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .mode-btn.active {
            background: rgba(255, 215, 0, 0.15);
            border-color: #FFD700;
            color: #FFD700;
        }

        button:active {
            transform: scale(0.95);
        }

        #main-btn:hover {
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
        }

        /* Progress Ring Animation */
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            stroke-linecap: round;
            stroke-dasharray: 880;
            /* 2 * PI * 140 */
            stroke-dashoffset: 0;
        }
    </style>

    <script>
        let timer;
        let timeLeft = 25 * 60;
        let totalTime = 25 * 60;
        let isRunning = false;
        let currentMode = 'focus';

        const circle = document.querySelector('.progress-ring__circle');
        const radius = circle.r.baseVal.value;
        const circumference = radius * 2 * Math.PI;

        circle.style.strokeDasharray = `${circumference} ${circumference}`;
        circle.style.strokeDashoffset = 0;

        function setProgress(percent) {
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;
        }

        function formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }

        function updateDisplay() {
            document.getElementById('timer-display').textContent = formatTime(timeLeft);
            const percent = (timeLeft / totalTime) * 100;
            setProgress(percent);
        }

        function toggleTimer() {
            const btn = document.getElementById('main-btn');
            if (isRunning) {
                clearInterval(timer);
                btn.textContent = 'Start';
                document.getElementById('timer-status').textContent = 'Paused';
            } else {
                timer = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateDisplay();
                    } else {
                        clearInterval(timer);
                        isRunning = false;
                        btn.textContent = 'Start';
                        document.getElementById('timer-status').textContent = 'Completed!';
                        // Play notification sound if desired
                    }
                }, 1000);
                btn.textContent = 'Pause';
                document.getElementById('timer-status').textContent = currentMode === 'focus' ? 'Focusing...' : 'Subtle Break...';
            }
            isRunning = !isRunning;
        }

        function resetTimer() {
            clearInterval(timer);
            isRunning = false;
            document.getElementById('main-btn').textContent = 'Start';

            switch (currentMode) {
                case 'focus':
                    timeLeft = 25 * 60;
                    break;
                case 'short':
                    timeLeft = 5 * 60;
                    break;
                case 'long':
                    timeLeft = 15 * 60;
                    break;
            }
            totalTime = timeLeft;
            updateDisplay();
            document.getElementById('timer-status').textContent = 'Ready';
            setProgress(100);
        }

        function setMode(mode) {
            currentMode = mode;
            // Update active buttons
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(`btn-${mode}`).classList.add('active');

            // Set times
            clearInterval(timer);
            isRunning = false;
            document.getElementById('main-btn').textContent = 'Start';

            switch (mode) {
                case 'focus':
                    timeLeft = 25 * 60;
                    document.getElementById('timer-status').textContent = 'Ready to Focus';
                    circle.style.stroke = '#FFD700'; // Gold
                    break;
                case 'short':
                    timeLeft = 5 * 60;
                    document.getElementById('timer-status').textContent = 'Time for a break';
                    circle.style.stroke = '#4ade80'; // Green
                    break;
                case 'long':
                    timeLeft = 15 * 60;
                    document.getElementById('timer-status').textContent = 'Deep Rest';
                    circle.style.stroke = '#60a5fa'; // Blue
                    break;
            }
            totalTime = timeLeft;
            updateDisplay();
            setProgress(100);
        }
    </script>
@endsection