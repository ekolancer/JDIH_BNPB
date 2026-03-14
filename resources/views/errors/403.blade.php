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

        /* Star field */
        #starfield {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        /* Red nebula blobs */
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

        .nb3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(200, 50, 0, 0.1), transparent 70%);
            top: 40%;
            left: 40%;
            animation: nbDrift3 18s ease-in-out infinite alternate;
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

        @keyframes nbDrift3 {
            to {
                transform: translate(-30px, 40px);
            }
        }

        /* Main layout */
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

        /* Shield / force field */
        .shield-wrap {
            position: relative;
            flex-shrink: 0;
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

        /* Rotating orbit rings */
        .orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px solid transparent;
            transform: translate(-50%, -50%);
        }

        .orbit-1 {
            width: 260px;
            height: 260px;
            border-color: rgba(220, 38, 38, 0.25);
            animation: orbitSpin1 8s linear infinite;
        }

        .orbit-2 {
            width: 300px;
            height: 300px;
            border-color: rgba(180, 0, 0, 0.15);
            border-style: dashed;
            animation: orbitSpin2 14s linear infinite reverse;
        }

        .orbit-3 {
            width: 340px;
            height: 340px;
            border-color: rgba(150, 0, 0, 0.1);
            animation: orbitSpin1 20s linear infinite;
        }

        @keyframes orbitSpin1 {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        @keyframes orbitSpin2 {
            to {
                transform: translate(-50%, -50%) rotate(-360deg);
            }
        }

        /* Satellite dots on orbits */
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

        .sat-sm {
            width: 5px;
            height: 5px;
            background: var(--amber);
            box-shadow: 0 0 6px var(--amber);
            top: auto;
            bottom: -2px;
        }

        /* Shield core */
        .shield-core {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle at 38% 35%,
                    #3d0000, #1a0000 55%, #0a0000);
            border: 2px solid rgba(220, 38, 38, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow:
                inset 0 0 40px rgba(220, 38, 38, 0.3),
                0 0 60px rgba(220, 38, 38, 0.3);
            overflow: hidden;
        }

        /* Shield hexagon pattern inside */
        .shield-core::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(60deg, rgba(220, 38, 38, 0.07) 0, rgba(220, 38, 38, 0.07) 1px, transparent 0, transparent 50%),
                repeating-linear-gradient(120deg, rgba(220, 38, 38, 0.07) 0, rgba(220, 38, 38, 0.07) 1px, transparent 0, transparent 50%),
                repeating-linear-gradient(0deg, rgba(220, 38, 38, 0.07) 0, rgba(220, 38, 38, 0.07) 1px, transparent 0, transparent 50%);
            background-size: 20px 20px;
            animation: hexShift 3s linear infinite;
        }

        @keyframes hexShift {
            to {
                background-position: 20px 20px;
            }
        }

        .lock-icon {
            font-size: 52px;
            position: relative;
            z-index: 2;
            animation: lockShake 0.5s ease-in-out infinite;
            filter: drop-shadow(0 0 12px rgba(220, 38, 38, 0.8));
        }

        @keyframes lockShake {

            0%,
            100% {
                transform: rotate(0deg) scale(1);
            }

            20% {
                transform: rotate(-3deg) scale(1.02);
            }

            40% {
                transform: rotate(3deg) scale(1.02);
            }

            60% {
                transform: rotate(-2deg) scale(1);
            }

            80% {
                transform: rotate(2deg) scale(1);
            }
        }

        /* Alert ring pulse */
        .alert-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 2px solid var(--red-glow);
            transform: translate(-50%, -50%);
            animation: alertExpand 2s ease-out infinite;
            pointer-events: none;
        }

        .alert-ring:nth-child(2) {
            animation-delay: 0.7s;
        }

        .alert-ring:nth-child(3) {
            animation-delay: 1.4s;
        }

        @keyframes alertExpand {
            0% {
                transform: translate(-50%, -50%) scale(0.9);
                opacity: 0.8;
            }

            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }

        /* Right info panel */
        .info-panel {
            max-width: 460px;
        }

        /* Access denied badge */
        .denied-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #7f1d1d, #991b1b);
            border: 1px solid rgba(220, 38, 38, 0.5);
            border-radius: 3px;
            padding: 6px 16px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 12px;
            letter-spacing: 3px;
            color: #fca5a5;
            margin-bottom: 24px;
            animation: badgePulse 2s ease-in-out infinite;
        }

        @keyframes badgePulse {

            0%,
            100% {
                box-shadow: 0 0 0 rgba(220, 38, 38, 0);
            }

            50% {
                box-shadow: 0 0 20px rgba(220, 38, 38, 0.3);
            }
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

        /* Error number */
        .err-num {
            font-family: 'Share Tech Mono', monospace;
            font-size: clamp(80px, 12vw, 130px);
            font-weight: 400;
            color: var(--red-glow);
            line-height: 0.9;
            text-shadow:
                0 0 30px rgba(220, 38, 38, 0.6),
                0 0 60px rgba(220, 38, 38, 0.3),
                2px 2px 0 rgba(0, 0, 0, 0.5);
            position: relative;
            margin-bottom: 8px;
            animation: numFlicker 6s ease-in-out infinite;
        }

        @keyframes numFlicker {

            0%,
            88%,
            92%,
            100% {
                opacity: 1;
            }

            89% {
                opacity: 0.6;
            }

            90% {
                opacity: 1;
            }

            91% {
                opacity: 0.4;
            }
        }

        .err-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            letter-spacing: 5px;
            color: rgba(220, 38, 38, 0.7);
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .desc {
            font-size: 17px;
            font-weight: 300;
            color: var(--text);
            line-height: 1.85;
            margin-bottom: 30px;
            border-left: 2px solid rgba(220, 38, 38, 0.4);
            padding-left: 18px;
        }

        .desc strong {
            color: #fca5a5;
            font-weight: 600;
        }

        /* Terminal block */
        .terminal {
            background: rgba(10, 0, 0, 0.7);
            border: 1px solid rgba(220, 38, 38, 0.2);
            border-radius: 6px;
            padding: 16px 20px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 12px;
            line-height: 2;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .terminal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(220, 38, 38, 0.5), transparent);
        }

        .t-line {
            display: flex;
            gap: 10px;
        }

        .t-pre {
            color: rgba(220, 38, 38, 0.6);
        }

        .t-ok {
            color: #4ade80;
        }

        .t-err {
            color: #f87171;
        }

        .t-warn {
            color: #fbbf24;
        }

        .t-dim {
            color: rgba(255, 255, 255, 0.3);
        }

        .cursor-blink {
            animation: cblink 1s step-end infinite;
        }

        @keyframes cblink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 14px;
        }

        .btn {
            padding: 13px 28px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.08) 50%, transparent 60%);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .btn:hover::after {
            transform: translateX(100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #991b1b, #dc2626);
            color: #fff;
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.3), 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .btn-primary:hover {
            box-shadow: 0 0 35px rgba(220, 38, 38, 0.6), 0 4px 16px rgba(0, 0, 0, 0.5);
            transform: translateY(-2px);
        }

        .btn-ghost {
            background: transparent;
            color: #fca5a5;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .btn-ghost:hover {
            background: rgba(220, 38, 38, 0.08);
            border-color: rgba(220, 38, 38, 0.6);
            transform: translateY(-2px);
        }

        /* HUD elements */
        .hud {
            position: fixed;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: rgba(220, 38, 38, 0.25);
            letter-spacing: 2px;
            pointer-events: none;
            z-index: 50;
            line-height: 1.8;
        }

        .hud-tl {
            top: 16px;
            left: 20px;
        }

        .hud-tr {
            top: 16px;
            right: 20px;
            text-align: right;
        }

        .hud-bl {
            bottom: 16px;
            left: 20px;
        }

        .hud-br {
            bottom: 16px;
            right: 20px;
            text-align: right;
        }

        /* Corner brackets */
        .bracket {
            position: fixed;
            width: 30px;
            height: 30px;
            z-index: 50;
            pointer-events: none;
        }

        .bracket-tl {
            top: 12px;
            left: 12px;
            border-top: 2px solid rgba(220, 38, 38, 0.3);
            border-left: 2px solid rgba(220, 38, 38, 0.3);
        }

        .bracket-tr {
            top: 12px;
            right: 12px;
            border-top: 2px solid rgba(220, 38, 38, 0.3);
            border-right: 2px solid rgba(220, 38, 38, 0.3);
        }

        .bracket-bl {
            bottom: 12px;
            left: 12px;
            border-bottom: 2px solid rgba(220, 38, 38, 0.3);
            border-left: 2px solid rgba(220, 38, 38, 0.3);
        }

        .bracket-br {
            bottom: 12px;
            right: 12px;
            border-bottom: 2px solid rgba(220, 38, 38, 0.3);
            border-right: 2px solid rgba(220, 38, 38, 0.3);
        }
    </style>
</head>

<body>

    <canvas id="starfield"></canvas>
    <div class="nebula nb1"></div>
    <div class="nebula nb2"></div>
    <div class="nebula nb3"></div>

    <!-- Corner brackets -->
    <div class="bracket bracket-tl"></div>
    <div class="bracket bracket-tr"></div>
    <div class="bracket bracket-bl"></div>
    <div class="bracket bracket-br"></div>

    <!-- HUD -->
    <div class="hud hud-tl">
        <div>SECURITY // GALACTIC PATROL</div>
        <div>CLEARANCE // DENIED</div>
        <div id="hud-time">STARDATE // 3025.0314</div>
    </div>
    <div class="hud hud-tr">
        <div>ZONE // RESTRICTED</div>
        <div>THREAT LVL // ALPHA</div>
        <div id="hud-id">UID // 00000000</div>
    </div>
    <div class="hud hud-bl">
        <div>AUTH // FAILED</div>
        <div>ATTEMPT // <span id="attempt-count">1</span> / 3</div>
    </div>
    <div class="hud hud-br">
        <div>SHIELD // ACTIVE</div>
        <div>LOCKDOWN // ENGAGED</div>
    </div>

    <div class="scene">

        <!-- Shield visual -->
        <div class="shield-wrap">
            <div class="orbit orbit-1">
                <div class="sat"></div>
            </div>
            <div class="orbit orbit-2">
                <div class="sat sat-sm"></div>
            </div>
            <div class="orbit orbit-3"></div>

            <div class="shield-outer">
                <div class="alert-ring"></div>
                <div class="alert-ring"></div>
                <div class="alert-ring"></div>

                <div class="shield-core">
                    <div class="lock-icon">🔒</div>
                </div>
            </div>
        </div>

        <!-- Info panel -->
        <div class="info-panel">

            <div class="denied-badge">
                <div class="denied-dot"></div>
                AKSES DITOLAK
            </div>

            <div class="err-num">403</div>
            <div class="err-label">FORBIDDEN // ZONA TERLARANG</div>

            <p class="desc">
                Koordinat ini berada di luar batas <strong>otorisasi Anda</strong>.<br>
                Sistem keamanan kami telah mendeteksi upaya akses tanpa izin.<br>
                Identitas Anda telah dicatat dan dilaporkan.
            </p>

            <div class="terminal">
                <div class="t-line"><span class="t-pre">AUTH &gt;</span><span>VERIFY credentials user_session</span></div>
                <div class="t-line"><span class="t-err">FAIL</span><span class="t-dim">&nbsp;Insufficient clearance level</span></div>
                <div class="t-line"><span class="t-pre">AUTH &gt;</span><span>REQUEST override_access</span></div>
                <div class="t-line"><span class="t-err">DENY</span><span class="t-dim">&nbsp;Permission: FORBIDDEN (403)</span></div>
                <div class="t-line"><span class="t-warn">WARN</span><span class="t-dim">&nbsp;Unauthorized access logged</span></div>
                <div class="t-line"><span class="t-pre">AUTH &gt;</span><span class="cursor-blink">_</span></div>
            </div>

            <div class="btn-row">
                <a href="/" class="btn btn-primary">Halaman Utama</a>
            </div>

        </div>
    </div>

    <script>
        // Stars
        const canvas = document.getElementById('starfield');
        const ctx = canvas.getContext('2d');

        function resize() {
            canvas.width = innerWidth;
            canvas.height = innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const stars = Array.from({
            length: 180
        }, () => ({
            x: Math.random() * innerWidth,
            y: Math.random() * innerHeight,
            r: Math.random() * 1.4,
            a: 0.2 + Math.random() * 0.7,
            sp: 0.003 + Math.random() * 0.006,
            ph: Math.random() * Math.PI * 2
        }));

        let t = 0;
        (function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                const alpha = s.a * (0.5 + 0.5 * Math.sin(t * s.sp + s.ph));
                // Tinge stars red
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${200 + Math.random()*30},${150 + Math.random()*40},${160 + Math.random()*40},${alpha})`;
                ctx.fill();
            });
            t++;
            requestAnimationFrame(draw);
        })();

        // HUD updates
        let attempts = 1;
        setInterval(() => {
            const now = new Date();
            document.getElementById('hud-time').textContent =
                `STARDATE // ${now.getFullYear()}.${String(now.getMonth()+1).padStart(2,'0')}${String(now.getDate()).padStart(2,'0')}`;
            document.getElementById('hud-id').textContent =
                `UID // ${Math.floor(Math.random()*0xFFFFFFFF).toString(16).toUpperCase().padStart(8,'0')}`;
        }, 2000);

        setInterval(() => {
            if (attempts < 3) attempts++;
            else attempts = 1;
            document.getElementById('attempt-count').textContent = attempts;
        }, 5000);
    </script>
</body>

</html>