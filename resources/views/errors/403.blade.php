<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Forbidden</title>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --void: #050308;
            --deep: #0a0515;
            --red-core: #dc2626;
            --red-glow: #ef4444;
            --red-dim: #7f1d1d;
            --amber: #f59e0b;
            --ice: #e0e7ff;
            --muted: #6b5a7e;
            --text: #d1c4e9;
            --shield: #1e0a0a;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: var(--void);
            color: var(--ice);
            font-family: 'Rajdhani', sans-serif;
            overflow: hidden;
        }

        /* Scanline overlay */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg,
                    transparent,
                    transparent 3px,
                    rgba(220, 38, 38, 0.018) 3px,
                    rgba(220, 38, 38, 0.018) 4px);
            pointer-events: none;
            z-index: 200;
            animation: scanShift 8s linear infinite;
        }

        @keyframes scanShift {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 0 100px;
            }
        }

        #starfield {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .nebula {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 1;
        }

        .nb1 {
            width: 700px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(180, 0, 0, 0.22), transparent 70%);
            top: -200px;
            right: -100px;
            animation: nbDrift1 20s ease-in-out infinite alternate;
        }

        .nb2 {
            width: 500px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(120, 0, 80, 0.15), transparent 70%);
            bottom: -150px;
            left: -80px;
            animation: nbDrift2 25s ease-in-out infinite alternate;
        }

        @keyframes nbDrift1 {
            to {
                transform: translate(-60px, 80px);
            }
        }

        @keyframes nbDrift2 {
            to {
                transform: translate(50px, -60px);
            }
        }

        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            gap: 80px;
            padding: 40px;
        }

        .shield-wrap {
            flex-shrink: 0;
            position: relative;
        }

        .shield-outer {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            border: 2px solid rgba(220, 38, 38, 0.4);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: shieldPulse 2s ease-in-out infinite;
        }

        @keyframes shieldPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(220, 38, 38, 0), 0 0 40px rgba(220, 38, 38, 0.2);
            }

            50% {
                box-shadow: 0 0 0 20px rgba(220, 38, 38, 0), 0 0 80px rgba(220, 38, 38, 0.4);
            }
        }

        .orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .orbit-1 {
            width: 260px;
            height: 260px;
            border: 1px solid rgba(220, 38, 38, 0.25);
            animation: orbitSpin1 8s linear infinite;
        }

        .sat {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--red-glow);
            box-shadow: 0 0 8px var(--red-glow);
            top: -4px;
            left: 50%;
            transform: translateX(-50%);
        }

        @keyframes orbitSpin1 {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        .shield-core {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle at 38% 35%, #3d0000, #1a0000 55%, #0a0000);
            border: 2px solid rgba(220, 38, 38, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .lock-icon {
            font-size: 52px;
            animation: lockShake 0.5s ease-in-out infinite;
            filter: drop-shadow(0 0 12px rgba(220, 38, 38, 0.8));
        }

        @keyframes lockShake {

            0%,
            100% {
                transform: rotate(0deg);
            }

            20% {
                transform: rotate(-3deg);
            }

            40% {
                transform: rotate(3deg);
            }
        }

        .info-panel {
            max-width: 460px;
        }

        .denied-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7f1d1d, #991b1b);
            border: 1px solid rgba(220, 38, 38, 0.5);
            padding: 6px 16px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            letter-spacing: 2px;
            color: #fca5a5;
            margin-bottom: 20px;
        }

        .denied-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ef4444;
            animation: dotBlink 0.8s step-end infinite;
        }

        @keyframes dotBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .err-num {
            font-family: 'Share Tech Mono', monospace;
            font-size: 100px;
            color: var(--red-glow);
            line-height: 0.9;
            text-shadow: 0 0 30px rgba(220, 38, 38, 0.6);
            margin-bottom: 10px;
        }

        .desc {
            font-size: 17px;
            font-weight: 300;
            color: var(--text);
            line-height: 1.6;
            margin-bottom: 25px;
            border-left: 2px solid var(--red-dim);
            padding-left: 15px;
        }

        /* REVISI COUNTDOWN CSS */
        .redirect-container {
            background: rgba(220, 38, 38, 0.05);
            padding: 15px;
            border: 1px solid rgba(220, 38, 38, 0.15);
            border-radius: 4px;
            margin-bottom: 25px;
            position: relative;
        }

        .redirect-text {
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            letter-spacing: 2px;
            color: rgba(220, 38, 38, 0.7);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #redirect-count {
            color: #ef4444;
            font-size: 20px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.8);
            animation: pulseRed 1s infinite;
        }

        @keyframes pulseRed {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }

        .bar-wrapper {
            width: 100%;
            height: 4px;
            background: rgba(220, 38, 38, 0.1);
            position: relative;
            overflow: hidden;
            border-radius: 2px;
        }

        #redirect-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #7f1d1d 0%, #ef4444 100%);
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
            transition: width 1s linear;
        }

        /* Tombol */
        .btn-row {
            display: flex;
            gap: 14px;
        }

        .btn {
            padding: 12px 24px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--red-core);
            color: white;
            border: none;
            box-shadow: 0 0 15px rgba(220, 38, 38, 0.3);
        }

        .btn-primary:hover {
            background: var(--red-glow);
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(220, 38, 38, 0.5);
        }

        .btn-ghost {
            background: transparent;
            color: #fca5a5;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .btn-ghost:hover {
            background: rgba(220, 38, 38, 0.1);
            border-color: var(--red-glow);
        }

        /* HUD */
        .hud {
            position: fixed;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: rgba(220, 38, 38, 0.3);
            pointer-events: none;
            z-index: 50;
        }

        .hud-tl {
            top: 20px;
            left: 20px;
        }

        .hud-br {
            bottom: 20px;
            right: 20px;
            text-align: right;
        }
    </style>
</head>

<body>

    <canvas id="starfield"></canvas>
    <div class="nebula nb1"></div>
    <div class="nebula nb2"></div>

    <div class="hud hud-tl">
        <div>SECURITY // GALACTIC PATROL</div>
        <div id="hud-time">STARDATE // 2026.0315</div>
    </div>
    <div class="hud hud-br">
        <div>ZONE // RESTRICTED</div>
        <div>SHIELD // ACTIVE</div>
    </div>

    <div class="scene">
        <div class="shield-wrap">
            <div class="orbit orbit-1">
                <div class="sat"></div>
            </div>
            <div class="shield-outer">
                <div class="shield-core">
                    <div class="lock-icon">🔒</div>
                </div>
            </div>
        </div>

        <div class="info-panel">
            <div class="denied-badge">
                <div class="denied-dot"></div> AKSES DITOLAK
            </div>

            <div class="err-num">403</div>
            <p class="desc">
                Sistem mendeteksi upaya akses tanpa otorisasi tingkat <strong>Alpha</strong>.<br>
                Protokol pengamanan mandiri telah diaktifkan.
            </p>

            <div class="redirect-container">
                <div class="redirect-text">
                    <span>MENGEMBALIKAN KE MARKAS DALAM...</span>
                    <div><span id="redirect-count">5</span>s</div>
                </div>
                <div class="bar-wrapper">
                    <div id="redirect-bar"></div>
                </div>
            </div>

            <div class="btn-row">
                <a href="javascript:history.back()" class="btn btn-primary">◀ MUNDUR</a>
                <a href="/" class="btn btn-ghost">⌂ MARKAS UTAMA</a>
            </div>
        </div>
    </div>

    <script>
        // Stars background
        const canvas = document.getElementById('starfield');
        const ctx = canvas.getContext('2d');

        function resize() {
            canvas.width = innerWidth;
            canvas.height = innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const stars = Array.from({
            length: 150
        }, () => ({
            x: Math.random() * innerWidth,
            y: Math.random() * innerHeight,
            r: Math.random() * 1.5,
            a: Math.random(),
            s: 0.005 + Math.random() * 0.01
        }));

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 200, 200, ${Math.abs(Math.sin(Date.now() * s.s))})`;
                ctx.fill();
            });
            requestAnimationFrame(draw);
        }
        draw();

        // Timer Logic
        (function() {
            const TOTAL = 5;
            let remaining = TOTAL;
            const countEl = document.getElementById('redirect-count');
            const barEl = document.getElementById('redirect-bar');

            const timer = setInterval(() => {
                remaining--;
                if (remaining >= 0) {
                    countEl.textContent = remaining;
                    barEl.style.width = ((remaining / TOTAL) * 100) + '%';
                }

                if (remaining <= 0) {
                    clearInterval(timer);
                    window.location.href = '/';
                }
            }, 1000);
        })();
    </script>
</body>

</html>