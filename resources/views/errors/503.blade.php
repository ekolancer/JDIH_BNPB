<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="300">
    <meta name="robots" content="noindex, nofollow">
    <title>Pemeliharaan Sistem — JDIH BNPB</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --void: #03020a;
            --teal-core: #14b8a6;
            --teal-glow: #2dd4bf;
            --gold: #f59e0b;
            --ice: #e2e8f0;
            --muted: #64748b;
            --text: #94a3b8;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: var(--void);
            color: var(--ice);
            font-family: 'Syne', sans-serif;
            overflow: hidden;
        }

        /* Scanline Effect */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 3px,
                    rgba(20, 184, 166, 0.015) 3px, rgba(20, 184, 166, 0.015) 4px);
            pointer-events: none;
            z-index: 300;
        }

        #cosmos {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .nebula {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 1;
            opacity: 0.6;
        }

        .nb1 {
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(20, 184, 166, 0.15), transparent 70%);
            top: -100px;
            left: -100px;
            animation: orbit 20s infinite alternate;
        }

        .nb2 {
            width: 600px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(245, 158, 11, 0.1), transparent 70%);
            bottom: -100px;
            right: -100px;
            animation: orbit 25s infinite alternate-reverse;
        }

        @keyframes orbit {
            to {
                transform: translate(50px, 50px);
            }
        }

        /* HUD Frame */
        .brk {
            position: fixed;
            width: 30px;
            height: 30px;
            z-index: 50;
            opacity: 0.3;
        }

        .brk::before {
            content: '';
            position: absolute;
            background: var(--teal-core);
            width: 100%;
            height: 1.5px;
        }

        .brk::after {
            content: '';
            position: absolute;
            background: var(--teal-core);
            width: 1.5px;
            height: 100%;
        }

        .brk-tl {
            top: 20px;
            left: 20px;
        }

        .brk-tr {
            top: 20px;
            right: 20px;
            transform: scaleX(-1);
        }

        .brk-bl {
            bottom: 20px;
            left: 20px;
            transform: scaleY(-1);
        }

        .brk-br {
            bottom: 20px;
            right: 20px;
            transform: scale(-1);
        }

        .hud {
            position: fixed;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 2px;
            color: rgba(20, 184, 166, 0.4);
            z-index: 50;
            text-transform: uppercase;
        }

        .hud-tl {
            top: 25px;
            left: 60px;
        }

        .hud-tr {
            top: 25px;
            right: 60px;
            text-align: right;
        }

        /* Main Scene */
        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 40px;
            text-align: center;
        }

        /* Satellite Animation */
        .satellite-wrap {
            position: relative;
            margin-bottom: 50px;
        }

        .satellite-svg {
            width: 180px;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 0 20px rgba(20, 184, 166, 0.3));
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

        .signal-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px solid var(--teal-core);
            transform: translate(-50%, -50%);
            animation: expand 4s linear infinite;
            opacity: 0;
        }

        @keyframes expand {
            0% {
                width: 50px;
                height: 50px;
                opacity: 0.5;
            }

            100% {
                width: 300px;
                height: 300px;
                opacity: 0;
            }
        }

        /* Typography */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(20, 184, 166, 0.1);
            border: 1px solid rgba(20, 184, 166, 0.3);
            border-radius: 50px;
            padding: 6px 18px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--teal-glow);
            letter-spacing: 3px;
            margin-bottom: 20px;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background: var(--gold);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5);
            }

            100% {
                box-shadow: 0 0 0 8px transparent;
            }
        }

        h1 {
            font-size: clamp(18px, 7vw, 36px);
            font-weight: 800;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: 10px;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fff 40%, var(--teal-glow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subhead {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            letter-spacing: 8px;
            color: var(--teal-core);
            margin-bottom: 40px;
            opacity: 0.7;
        }

        .desc-box {
            max-width: 600px;
            border-left: 2px solid rgba(20, 184, 166, 0.3);
            padding-left: 25px;
            text-align: left;
            margin-bottom: 40px;
        }

        .main-desc {
            font-size: 18px;
            color: var(--ice);
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .secondary-desc {
            font-size: 14px;
            color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
        }

        strong {
            color: var(--teal-glow);
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 14px 28px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            font-family: 'Syne', sans-serif;
        }

        .btn-primary {
            background: var(--teal-core);
            color: var(--void);
            border: 1px solid var(--teal-core);
        }

        .btn-primary:hover {
            background: transparent;
            color: var(--teal-glow);
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.4);
        }

        .btn-ghost {
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--muted);
        }

        .btn-ghost:hover {
            border-color: var(--ice);
            color: var(--ice);
        }
    </style>
</head>

<body>
    <canvas id="cosmos"></canvas>
    <div class="nebula nb1"></div>
    <div class="nebula nb2"></div>

    <div class="brk brk-tl"></div>
    <div class="brk brk-tr"></div>
    <div class="brk brk-bl"></div>
    <div class="brk brk-br"></div>

    <div class="hud hud-tl">JDIH BNPB // SISTEM INFORMASI<br>MODE // MAINTENANCE_PROTOCOL</div>
    <div class="hud hud-tr">STATUS // TERJADWAL<br><span id="hud-time">UTC // --:--:--</span></div>

    <div class="scene">
        <div class="satellite-wrap">
            <div class="signal-ring"></div>
            <div class="signal-ring" style="animation-delay: 1s;"></div>
            <svg class="satellite-svg" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="70" width="40" height="20" rx="2" fill="rgba(20,184,166,0.1)" stroke="var(--teal-core)" stroke-width="1.5" />
                <rect x="110" y="70" width="40" height="20" rx="2" fill="rgba(20,184,166,0.1)" stroke="var(--teal-core)" stroke-width="1.5" />
                <rect x="55" y="55" width="50" height="50" rx="5" fill="#0a1929" stroke="var(--teal-core)" stroke-width="2" />
                <circle cx="80" cy="80" r="12" stroke="var(--teal-core)" stroke-width="1.5" stroke-dasharray="4 2" />
                <path d="M80 30V55M80 105V130" stroke="var(--teal-core)" stroke-width="2" />
                <circle cx="80" cy="30" r="4" fill="var(--gold)" />
            </svg>
        </div>

        <div class="badge">
            <div class="badge-dot"></div>
            Maintenance Mode
        </div>

        <h1>PEMELIHARAAN SISTEM</h1>

        <div class="desc-box" style="text-align: center;">
            <p class="main-desc">
                Mohon maaf atas ketidaknyamanannya. Saat ini kami sedang melakukan
                <strong>pemeliharaan rutin</strong> untuk meningkatkan performa sistem.
            </p>
            <p class="secondary-desc">
                Proses ini diperlukan untuk sinkronisasi data JDIH BNPB. Kami akan segera kembali.
            </p>
        </div>

        <div class="btn-row">
            <a href="jdih@bnpb.go.id" class="btn btn-primary">Hubungi Admin</a>
            <a href="javascript:location.reload()" class="btn btn-ghost">Refresh Status</a>
        </div>
    </div>

    <script>
        // Cosmos Background
        const canvas = document.getElementById('cosmos');
        const ctx = canvas.getContext('2d');
        let stars = [];

        function init() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            stars = Array.from({
                length: 150
            }, () => ({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 1.5,
                opacity: Math.random(),
                speed: 0.005 + Math.random() * 0.01
            }));
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                s.opacity += s.speed;
                if (s.opacity > 1 || s.opacity < 0.1) s.speed *= -1;
                ctx.fillStyle = `rgba(226, 232, 240, ${s.opacity})`;
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(animate);
        }

        // Time HUD
        function updateTime() {
            const now = new Date();
            document.getElementById('hud-time').textContent = `UTC // ${now.getUTCHours().toString().padStart(2, '0')}:${now.getUTCMinutes().toString().padStart(2, '0')}:${now.getUTCSeconds().toString().padStart(2, '0')}`;
        }

        window.addEventListener('resize', init);
        init();
        animate();
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>