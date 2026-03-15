<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Tersesat di Luar Angkasa</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --void: #02010a;
            --deep: #060318;
            --nebula-1: #1a0533;
            --nebula-2: #0d1f4a;
            --star: #e8dff5;
            --glow: #a855f7;
            --cyan: #06b6d4;
            --warn: #f59e0b;
            --text: #c4b5fd;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: var(--void);
            color: var(--star);
            font-family: 'Exo 2', sans-serif;
            overflow: hidden;
            cursor: none;
        }

        /* Custom cursor */
        .cursor {
            position: fixed;
            width: 12px;
            height: 12px;
            background: var(--cyan);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.1s;
            box-shadow: 0 0 12px var(--cyan), 0 0 24px var(--cyan);
        }

        .cursor-ring {
            position: fixed;
            width: 36px;
            height: 36px;
            border: 1px solid var(--glow);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9998;
            transition: transform 0.15s, width 0.3s, height 0.3s;
            transform: translate(-50%, -50%);
        }

        #stars {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .nebula {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            pointer-events: none;
        }

        .nebula-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #7c3aed, transparent 70%);
            top: -150px;
            left: -150px;
            animation: drift1 18s ease-in-out infinite alternate;
        }

        .nebula-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #0ea5e9, transparent 70%);
            bottom: -100px;
            right: -100px;
            animation: drift2 22s ease-in-out infinite alternate;
        }

        @keyframes drift1 {
            to {
                transform: translate(60px, 40px);
                opacity: 0.25;
            }
        }

        @keyframes drift2 {
            to {
                transform: translate(-50px, -30px);
                opacity: 0.22;
            }
        }

        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        /* Planet & Astronaut */
        .planet-wrap {
            position: relative;
            margin-bottom: -20px;
        }

        .planet {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #3b1d6e, #1a0d3d 50%, #0a0520);
            box-shadow: inset -30px -20px 60px rgba(0, 0, 0, 0.8), 0 0 60px rgba(139, 92, 246, 0.4);
            animation: planetFloat 6s ease-in-out infinite;
        }

        .astronaut {
            position: absolute;
            top: -10px;
            right: -50px;
            font-size: 52px;
            animation: tumble 8s linear infinite;
        }

        @keyframes tumble {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes planetFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-14px);
            }
        }

        .error-code {
            font-family: 'Orbitron', monospace;
            font-size: clamp(80px, 15vw, 140px);
            font-weight: 900;
            background: linear-gradient(135deg, #e879f9, #a855f7 40%, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .tagline {
            font-family: 'Orbitron', monospace;
            font-size: 12px;
            letter-spacing: 5px;
            color: var(--cyan);
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .message {
            font-size: 16px;
            color: var(--text);
            text-align: center;
            max-width: 400px;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        /* REFACTORED COUNTDOWN CSS */
        .nav-relink-panel {
            width: 340px;
            background: rgba(6, 182, 212, 0.03);
            border-top: 1px solid rgba(6, 182, 212, 0.15);
            border-bottom: 1px solid rgba(6, 182, 212, 0.15);
            padding: 18px 0;
            margin-bottom: 30px;
            position: relative;
        }

        .nav-label {
            font-family: 'Orbitron', monospace;
            font-size: 9px;
            letter-spacing: 2px;
            color: rgba(6, 182, 212, 0.5);
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .nav-counter-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        #redirect-count {
            font-family: 'Orbitron', monospace;
            color: var(--cyan);
            font-size: 24px;
            font-weight: 900;
            text-shadow: 0 0 15px rgba(6, 182, 212, 0.6);
            min-width: 35px;
        }

        .bar-container {
            flex-grow: 1;
            height: 4px;
            background: rgba(6, 182, 212, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        #redirect-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #7c3aed, #06b6d4);
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.4);
            transition: width 1s linear;
        }

        .pulse-warning {
            animation: flashRed 0.5s infinite alternate;
        }

        @keyframes flashRed {
            from {
                opacity: 1;
            }

            to {
                opacity: 0.4;
                color: #f87171;
            }
        }

        /* Buttons */
        .btn-group {
            display: flex;
            gap: 14px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 6px;
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            text-transform: uppercase;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
            border: none;
        }

        .btn-secondary {
            background: transparent;
            color: var(--cyan);
            border: 1px solid rgba(6, 182, 212, 0.4);
        }

        .btn-secondary:hover {
            background: rgba(6, 182, 212, 0.1);
            border-color: var(--cyan);
        }

        /* HUD */
        .hud {
            position: fixed;
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            color: rgba(6, 182, 212, 0.3);
            pointer-events: none;
        }

        .hud-tl {
            top: 20px;
            left: 24px;
        }

        .hud-tr {
            top: 20px;
            right: 24px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>
    <canvas id="stars"></canvas>

    <div class="hud hud-tl">
        <div>SYS // JDIH-NAV</div>
        <div id="coords">LAT: 0.000 | LON: 0.000</div>
    </div>
    <div class="hud hud-tr">
        <div>SIGNAL // LOST</div>
        <div id="clock">T+00:00:00</div>
    </div>

    <div class="scene">
        <div class="planet-wrap">
            <div class="planet"></div>
            <div class="astronaut">👨‍🚀</div>
        </div>

        <div class="error-code">404</div>
        <div class="tagline">Halaman tidak ditemukan</div>

        <p class="message">
            Sinyal koneksi terputus. Halaman yang Anda cari telah melayang ke galaksi yang tidak diketahui.
        </p>

        <div class="nav-relink-panel">
            <div class="nav-label">
                <span>AUTO-RELINK TO HOMEBASE</span>
                <span id="sync-percent">100%</span>
            </div>
            <div class="nav-counter-wrapper">
                <div id="redirect-count">5</div>
                <div class="bar-container">
                    <div id="redirect-bar"></div>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <a href="javascript:history.back()" class="btn btn-primary">⬅ Kembali</a>
            <a href="/" class="btn btn-secondary">🏠 Home Base</a>
        </div>
    </div>

    <script>
        // Stars Background
        const canvas = document.getElementById('stars');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const stars = Array.from({
            length: 150
        }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.5,
            s: 0.002 + Math.random() * 0.005,
            p: Math.random() * Math.PI * 2
        }));

        function drawStars() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(232, 223, 245, ${0.3 + Math.abs(Math.sin(Date.now() * s.s + s.p))})`;
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();

        // Custom Cursor
        const cursor = document.getElementById('cursor');
        const ring = document.getElementById('cursorRing');
        let mx = 0,
            my = 0,
            rx = 0,
            ry = 0;
        document.addEventListener('mousemove', e => {
            mx = e.clientX;
            my = e.clientY;
        });

        function animCursor() {
            cursor.style.transform = `translate(${mx - 6}px, ${my - 6}px)`;
            rx += (mx - rx) * 0.15;
            ry += (my - ry) * 0.15;
            ring.style.transform = `translate(${rx - 18}px, ${ry - 18}px)`;
            requestAnimationFrame(animCursor);
        }
        animCursor();

        // HUD Clock & Coords
        const start = Date.now();
        setInterval(() => {
            const s = Math.floor((Date.now() - start) / 1000);
            const m = String(Math.floor(s / 60)).padStart(2, '0');
            const sec = String(s % 60).padStart(2, '0');
            document.getElementById('clock').textContent = `T+00:${m}:${sec}`;
            document.getElementById('coords').textContent = `LAT: ${(Math.sin(s)*10).toFixed(3)} | LON: ${(Math.cos(s)*10).toFixed(3)}`;
        }, 1000);

        // REFACTORED REDIRECT LOGIC
        (function() {
            const TOTAL = 5;
            let remaining = TOTAL;
            const countEl = document.getElementById('redirect-count');
            const barEl = document.getElementById('redirect-bar');
            const percentEl = document.getElementById('sync-percent');

            barEl.style.width = '100%';

            const timer = setInterval(() => {
                remaining--;
                if (remaining >= 0) {
                    countEl.textContent = remaining;
                    const ratio = (remaining / TOTAL);
                    barEl.style.width = (ratio * 100) + '%';
                    percentEl.textContent = Math.round(ratio * 100) + '%';

                    if (remaining <= 2) countEl.classList.add('pulse-warning');
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