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

        /* Star canvas */
        #stars {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        /* Nebula blobs */
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

        .nebula-3 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #ec4899, transparent 70%);
            top: 40%;
            left: 55%;
            animation: drift3 16s ease-in-out infinite alternate;
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

        @keyframes drift3 {
            to {
                transform: translate(-40px, 50px);
                opacity: 0.15;
            }
        }

        /* Shooting stars */
        .shoot {
            position: fixed;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            pointer-events: none;
        }

        .shoot::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 1px;
            background: linear-gradient(to left, rgba(255, 255, 255, 0.8), transparent);
            transform-origin: right center;
            transform: rotate(180deg);
        }

        /* Main layout */
        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            gap: 0;
        }

        /* Planet */
        .planet-wrap {
            position: relative;
            margin-bottom: -20px;
        }

        .planet {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%,
                    #3b1d6e, #1a0d3d 50%, #0a0520);
            box-shadow:
                inset -30px -20px 60px rgba(0, 0, 0, 0.8),
                inset 10px 8px 30px rgba(168, 85, 247, 0.3),
                0 0 60px rgba(139, 92, 246, 0.4),
                0 0 120px rgba(139, 92, 246, 0.15);
            animation: planetFloat 6s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        .planet::before {
            content: '';
            position: absolute;
            top: 25%;
            left: 10%;
            width: 80%;
            height: 15%;
            background: rgba(168, 85, 247, 0.15);
            border-radius: 50%;
            filter: blur(6px);
        }

        .planet::after {
            content: '';
            position: absolute;
            top: 45%;
            left: 5%;
            width: 90%;
            height: 8%;
            background: rgba(99, 102, 241, 0.2);
            border-radius: 50%;
            filter: blur(4px);
        }

        /* Ring */
        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 260px;
            height: 50px;
            border: 12px solid transparent;
            border-radius: 50%;
            background: transparent;
            border-color: rgba(168, 85, 247, 0.5) transparent rgba(168, 85, 247, 0.3) transparent;
            transform: translate(-50%, -50%) rotateX(75deg);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
            animation: planetFloat 6s ease-in-out infinite;
        }

        /* Astronaut */
        .astronaut {
            position: absolute;
            top: -10px;
            right: -50px;
            font-size: 52px;
            animation: tumble 8s linear infinite;
            filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.6));
        }

        @keyframes tumble {
            0% {
                transform: rotate(0deg) translateX(0);
            }

            25% {
                transform: rotate(90deg) translateX(5px);
            }

            50% {
                transform: rotate(180deg) translateX(0);
            }

            75% {
                transform: rotate(270deg) translateX(-5px);
            }

            100% {
                transform: rotate(360deg) translateX(0);
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

        /* 404 text */
        .error-code {
            font-family: 'Orbitron', monospace;
            font-size: clamp(80px, 15vw, 140px);
            font-weight: 900;
            letter-spacing: -4px;
            line-height: 1;
            background: linear-gradient(135deg, #e879f9, #a855f7 40%, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            position: relative;
            animation: glitch 5s infinite;
        }

        .error-code::before,
        .error-code::after {
            content: '404';
            position: absolute;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #e879f9, #a855f7 40%, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .error-code::before {
            animation: glitch-1 5s infinite;
            clip-path: polygon(0 0, 100% 0, 100% 35%, 0 35%);
        }

        .error-code::after {
            animation: glitch-2 5s infinite;
            clip-path: polygon(0 65%, 100% 65%, 100% 100%, 0 100%);
        }

        @keyframes glitch {

            0%,
            90%,
            100% {
                transform: translate(0);
            }

            92% {
                transform: translate(-2px, 1px);
            }

            94% {
                transform: translate(2px, -1px);
            }

            96% {
                transform: translate(-1px, 2px);
            }
        }

        @keyframes glitch-1 {

            0%,
            90%,
            100% {
                transform: translate(0);
                opacity: 0;
            }

            92% {
                transform: translate(3px, 0);
                opacity: 0.7;
                filter: hue-rotate(90deg);
            }

            94% {
                transform: translate(-3px, 0);
                opacity: 0.7;
            }

            96% {
                opacity: 0;
            }
        }

        @keyframes glitch-2 {

            0%,
            90%,
            100% {
                transform: translate(0);
                opacity: 0;
            }

            93% {
                transform: translate(-3px, 0);
                opacity: 0.7;
                filter: hue-rotate(-90deg);
            }

            95% {
                transform: translate(3px, 0);
                opacity: 0.7;
            }

            97% {
                opacity: 0;
            }
        }

        .tagline {
            font-family: 'Orbitron', monospace;
            font-size: clamp(11px, 2vw, 14px);
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--cyan);
            margin-top: -8px;
            margin-bottom: 24px;
            opacity: 0.85;
        }

        .message {
            font-size: 16px;
            font-weight: 300;
            color: var(--text);
            text-align: center;
            max-width: 400px;
            line-height: 1.8;
            margin-bottom: 36px;
            opacity: 0.8;
        }

        .message strong {
            color: white;
            font-weight: 600;
        }

        /* Console log */
        .console {
            background: rgba(6, 182, 212, 0.05);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 8px;
            padding: 14px 20px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: rgba(6, 182, 212, 0.8);
            margin-bottom: 36px;
            width: 340px;
            line-height: 1.9;
        }

        .console .prompt {
            color: var(--glow);
        }

        .console .ok {
            color: #4ade80;
        }

        .console .err {
            color: #f87171;
        }

        .blink {
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* Button */
        .btn-group {
            display: flex;
            gap: 14px;
        }

        .btn {
            padding: 13px 30px;
            border-radius: 6px;
            font-family: 'Orbitron', monospace;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .btn:hover::before {
            transform: translateX(100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
            color: white;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.4), 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-primary:hover {
            box-shadow: 0 0 35px rgba(168, 85, 247, 0.7), 0 4px 20px rgba(0, 0, 0, 0.4);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: var(--cyan);
            border: 1px solid rgba(6, 182, 212, 0.4);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(6, 182, 212, 0.1);
            border-color: var(--cyan);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.3);
            transform: translateY(-2px);
        }

        /* Signal lines */
        .signal {
            position: fixed;
            pointer-events: none;
            opacity: 0.06;
        }

        .signal-h {
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--cyan), transparent);
            animation: scanH 8s linear infinite;
        }

        .signal-v {
            height: 100%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, var(--glow), transparent);
            animation: scanV 12s linear infinite;
            left: 0;
        }

        @keyframes scanH {
            0% {
                top: 0;
                opacity: 0;
            }

            10% {
                opacity: 0.06;
            }

            90% {
                opacity: 0.06;
            }

            100% {
                top: 100%;
                opacity: 0;
            }
        }

        @keyframes scanV {
            0% {
                left: 0;
                opacity: 0;
            }

            10% {
                opacity: 0.04;
            }

            90% {
                opacity: 0.04;
            }

            100% {
                left: 100%;
                opacity: 0;
            }
        }

        /* Floating particles */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            animation: floatParticle linear infinite;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(720deg);
                opacity: 0;
            }
        }

        /* Coordinates HUD */
        .hud {
            position: fixed;
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            color: rgba(6, 182, 212, 0.3);
            letter-spacing: 2px;
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

        .hud-bl {
            bottom: 20px;
            left: 24px;
        }

        .hud-br {
            bottom: 20px;
            right: 24px;
            text-align: right;
        }

        .hud div {
            margin: 3px 0;
        }
    </style>
</head>

<body>

    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <canvas id="stars"></canvas>

    <div class="nebula nebula-1"></div>
    <div class="nebula nebula-2"></div>
    <div class="nebula nebula-3"></div>

    <div class="signal signal-h"></div>
    <div class="signal signal-v"></div>

    <!-- HUD corners -->
    <div class="hud hud-tl">
        <div>SYS // JDIH-NAV</div>
        <div>STATUS // OFFLINE</div>
        <div id="coords">LAT: 00.000 | LON: 00.000</div>
    </div>
    <div class="hud hud-tr">
        <div>SECTOR // UNKNOWN</div>
        <div>SIGNAL // LOST</div>
        <div id="clock">T+00:00:00</div>
    </div>
    <div class="hud hud-bl">
        <div>ERR_CODE // 404</div>
        <div>ROUTE // NULL</div>
    </div>
    <div class="hud hud-br">
        <div>FUEL // 0%</div>
        <div>OXYGEN // CRITICAL</div>
    </div>

    <div class="scene">
        <div class="planet-wrap">
            <div class="planet"></div>
            <div class="ring"></div>
            <div class="astronaut">👨‍🚀</div>
        </div>

        <div class="error-code">404</div>
        <div class="tagline">Halaman tidak ditemukan</div>

        <p class="message">
            Sinyal koneksi terputus.<br>
            <strong>Halaman yang Anda cari</strong> telah melayang ke suatu tempat di galaksi yang tidak diketahui.<br>
            Misi pencarian telah gagal.
        </p>

        <div class="console">
            <div><span class="prompt">SYS &gt;</span> PING destination.route</div>
            <div><span class="err">ERR</span> &nbsp;Request timeout — no response</div>
            <div><span class="prompt">SYS &gt;</span> LOCATE page_id=404</div>
            <div><span class="err">ERR</span> &nbsp;Object not found in known universe</div>
            <div><span class="prompt">SYS &gt;</span> <span class="blink">_</span></div>
        </div>

        <div class="btn-group">
            <a href="javascript:history.back()" class="btn btn-primary">⬅ Kembali</a>
        </div>
    </div>

    <script>
        // Stars
        const canvas = document.getElementById('stars');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const stars = Array.from({
            length: 220
        }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 1.5,
            a: Math.random(),
            speed: 0.003 + Math.random() * 0.008,
            phase: Math.random() * Math.PI * 2
        }));

        function drawStars(t) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                const alpha = s.a * (0.5 + 0.5 * Math.sin(t * s.speed + s.phase));
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(232,223,245,${alpha})`;
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        requestAnimationFrame(drawStars);

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });

        // Shooting stars
        function shoot() {
            const el = document.createElement('div');
            el.className = 'shoot';
            const angle = 20 + Math.random() * 20;
            el.style.cssText = `
      top: ${Math.random() * 60}%;
      left: ${Math.random() * 80}%;
      transform: rotate(${angle}deg);
    `;
            document.body.appendChild(el);
            el.animate([{
                    transform: `rotate(${angle}deg) translate(0, 0)`,
                    opacity: 1
                },
                {
                    transform: `rotate(${angle}deg) translate(300px, 0)`,
                    opacity: 0
                }
            ], {
                duration: 800,
                easing: 'ease-in'
            }).onfinish = () => el.remove();
        }
        setInterval(shoot, 2500 + Math.random() * 2000);

        // Particles
        const colors = ['#a855f7', '#06b6d4', '#ec4899', '#f59e0b'];
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = 2 + Math.random() * 4;
            p.style.cssText = `
      width: ${size}px; height: ${size}px;
      background: ${colors[Math.floor(Math.random() * colors.length)]};
      left: ${Math.random() * 100}%;
      animation-duration: ${8 + Math.random() * 15}s;
      animation-delay: ${Math.random() * 10}s;
      opacity: ${0.3 + Math.random() * 0.5};
    `;
            document.body.appendChild(p);
        }

        // Custom cursor
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
            rx += (mx - rx) * 0.12;
            ry += (my - ry) * 0.12;
            ring.style.transform = `translate(${rx - 18}px, ${ry - 18}px)`;
            requestAnimationFrame(animCursor);
        }
        animCursor();

        // HUD clock
        const start = Date.now();
        setInterval(() => {
            const s = Math.floor((Date.now() - start) / 1000);
            const h = String(Math.floor(s / 3600)).padStart(2, '0');
            const m = String(Math.floor((s % 3600) / 60)).padStart(2, '0');
            const sec = String(s % 60).padStart(2, '0');
            document.getElementById('clock').textContent = `T+${h}:${m}:${sec}`;

            const lat = (Math.sin(s * 0.01) * 90).toFixed(3);
            const lon = (Math.cos(s * 0.007) * 180).toFixed(3);
            document.getElementById('coords').textContent = `LAT: ${lat} | LON: ${lon}`;
        }, 1000);
    </script>
</body>

</html>