<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="300">
    <title>Pemeliharaan Sistem — JDIH</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@300;400;500&display=swap" rel="stylesheet">
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
            --teal-dim: #0d9488;
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

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 3px,
                    rgba(20, 184, 166, 0.013) 3px, rgba(20, 184, 166, 0.013) 4px);
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
        }

        .nb1 {
            width: 900px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(20, 184, 166, 0.14), transparent 70%);
            top: -200px;
            left: -200px;
            animation: nb1 24s ease-in-out infinite alternate;
        }

        .nb2 {
            width: 700px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(245, 158, 11, 0.09), transparent 70%);
            bottom: -180px;
            right: -150px;
            animation: nb2 30s ease-in-out infinite alternate;
        }

        .nb3 {
            width: 500px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(99, 102, 241, 0.1), transparent 70%);
            top: 30%;
            left: 45%;
            animation: nb3 20s ease-in-out infinite alternate;
        }

        @keyframes nb1 {
            to {
                transform: translate(80px, 60px);
            }
        }

        @keyframes nb2 {
            to {
                transform: translate(-60px, -50px);
            }
        }

        @keyframes nb3 {
            to {
                transform: translate(-50px, 40px);
            }
        }

        .brk {
            position: fixed;
            width: 28px;
            height: 28px;
            z-index: 50;
            pointer-events: none;
            opacity: 0.3;
        }

        .brk::before,
        .brk::after {
            content: '';
            position: absolute;
            background: var(--teal-core);
        }

        .brk::before {
            width: 100%;
            height: 1.5px;
            top: 0;
            left: 0;
        }

        .brk::after {
            width: 1.5px;
            height: 100%;
            top: 0;
            left: 0;
        }

        .brk-tl {
            top: 16px;
            left: 16px;
        }

        .brk-tr {
            top: 16px;
            right: 16px;
            transform: scaleX(-1);
        }

        .brk-bl {
            bottom: 16px;
            left: 16px;
            transform: scaleY(-1);
        }

        .brk-br {
            bottom: 16px;
            right: 16px;
            transform: scale(-1);
        }

        .hud {
            position: fixed;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 1.5px;
            color: rgba(20, 184, 166, 0.22);
            pointer-events: none;
            z-index: 50;
            line-height: 1.9;
            text-transform: uppercase;
        }

        .hud-tl {
            top: 18px;
            left: 20px;
        }

        .hud-tr {
            top: 18px;
            right: 20px;
            text-align: right;
        }

        .hud-bl {
            bottom: 18px;
            left: 20px;
        }

        .hud-br {
            bottom: 18px;
            right: 20px;
            text-align: right;
        }

        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 60px 24px;
            text-align: center;
        }

        /* Satellite */
        .satellite-wrap {
            position: relative;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .satellite-svg {
            width: 160px;
            animation: satelliteOrbit 10s ease-in-out infinite;
            filter: drop-shadow(0 0 18px rgba(20, 184, 166, 0.35));
        }

        @keyframes satelliteOrbit {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(2deg);
            }

            50% {
                transform: translateY(-18px) rotate(0deg);
            }

            75% {
                transform: translateY(-10px) rotate(-2deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .signal-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px solid rgba(20, 184, 166, 0.3);
            transform: translate(-50%, -50%);
            animation: signalExpand 3s ease-out infinite;
            pointer-events: none;
        }

        .signal-ring:nth-child(2) {
            animation-delay: 1s;
        }

        .signal-ring:nth-child(3) {
            animation-delay: 2s;
        }

        @keyframes signalExpand {
            0% {
                width: 40px;
                height: 40px;
                opacity: 0.8;
            }

            100% {
                width: 220px;
                height: 220px;
                opacity: 0;
            }
        }

        .gear-anim {
            animation: gearSpin 4s linear infinite;
            transform-origin: center;
        }

        @keyframes gearSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .ant-blink {
            animation: antBlink 1.2s ease-in-out infinite;
        }

        @keyframes antBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.15;
            }
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(20, 184, 166, 0.08);
            border: 1px solid rgba(20, 184, 166, 0.25);
            border-radius: 100px;
            padding: 5px 16px 5px 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 2.5px;
            color: var(--teal-glow);
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            animation: badgePulse 1.5s ease-in-out infinite;
        }

        @keyframes badgePulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }

            50% {
                box-shadow: 0 0 0 5px rgba(245, 158, 11, 0);
            }
        }

        h1 {
            font-size: clamp(28px, 5vw, 46px);
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #e2e8f0 30%, var(--teal-glow));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subhead {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            letter-spacing: 4px;
            color: rgba(20, 184, 166, 0.5);
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .desc {
            font-size: 15px;
            font-weight: 400;
            color: var(--text);
            line-height: 1.9;
            max-width: 460px;
            margin-bottom: 40px;
            border-left: 2px solid rgba(20, 184, 166, 0.25);
            padding-left: 18px;
            text-align: left;
        }

        .desc strong {
            color: var(--ice);
            font-weight: 600;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 26px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.25s;
        }

        .btn-primary {
            background: rgba(20, 184, 166, 0.12);
            color: var(--teal-glow);
            border: 1px solid rgba(20, 184, 166, 0.3);
        }

        .btn-primary:hover {
            background: rgba(20, 184, 166, 0.2);
            border-color: var(--teal-core);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(20, 184, 166, 0.15);
        }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .btn-ghost:hover {
            color: var(--ice);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .shoot {
            position: fixed;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            z-index: 2;
        }

        .shoot::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 1px;
            background: linear-gradient(to left, rgba(255, 255, 255, 0.7), transparent);
            transform: rotate(180deg);
        }

        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 2;
            animation: particleFloat linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) scale(0.5);
                opacity: 0;
            }

            10% {
                opacity: 0.7;
            }

            90% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-100px) scale(1.5);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <canvas id="cosmos"></canvas>
    <div class="nebula nb1"></div>
    <div class="nebula nb2"></div>
    <div class="nebula nb3"></div>

    <div class="brk brk-tl"></div>
    <div class="brk brk-tr"></div>
    <div class="brk brk-bl"></div>
    <div class="brk brk-br"></div>

    <div class="hud hud-tl">
        <div>JDIH // SISTEM INFORMASI</div>
        <div>MODE // MAINTENANCE</div>
    </div>
    <div class="hud hud-tr">
        <div>STATUS // SCHEDULED</div>
        <div id="hud-time">UTC // --:--:--</div>
    </div>
    <div class="hud hud-bl">
        <div>PAGE AUTO-REFRESH // 5 MIN</div>
    </div>
    <div class="hud hud-br">
        <div>TEAM // ON SITE</div>
    </div>

    <div class="scene">

        <div class="satellite-wrap">
            <div class="signal-ring"></div>
            <div class="signal-ring"></div>
            <div class="signal-ring"></div>

            <svg class="satellite-svg" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="8" y="68" width="44" height="24" rx="3" fill="#0a1f2e" stroke="#14b8a6" stroke-width="1.2" />
                <line x1="8" y1="76" x2="52" y2="76" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="8" y1="82" x2="52" y2="82" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="8" y1="88" x2="52" y2="88" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="22" y1="68" x2="22" y2="92" stroke="rgba(20,184,166,0.25)" stroke-width="1" />
                <line x1="36" y1="68" x2="36" y2="92" stroke="rgba(20,184,166,0.25)" stroke-width="1" />
                <rect x="8" y="68" width="44" height="24" rx="3" fill="rgba(20,184,166,0.07)" />
                <rect x="50" y="77" width="18" height="6" rx="2" fill="#0d2a3a" stroke="rgba(20,184,166,0.4)" stroke-width="1" />
                <rect x="56" y="56" width="48" height="48" rx="6" fill="#0a1929" stroke="#14b8a6" stroke-width="1.5" />
                <rect x="64" y="64" width="32" height="32" rx="4" fill="#061420" stroke="rgba(20,184,166,0.3)" stroke-width="1" />
                <g class="gear-anim" style="transform-origin: 80px 80px;">
                    <circle cx="80" cy="80" r="10" fill="none" stroke="rgba(20,184,166,0.5)" stroke-width="1.5" />
                    <circle cx="80" cy="80" r="4" fill="rgba(20,184,166,0.3)" />
                    <line x1="80" y1="67" x2="80" y2="72" stroke="rgba(20,184,166,0.5)" stroke-width="2" />
                    <line x1="80" y1="88" x2="80" y2="93" stroke="rgba(20,184,166,0.5)" stroke-width="2" />
                    <line x1="67" y1="80" x2="72" y2="80" stroke="rgba(20,184,166,0.5)" stroke-width="2" />
                    <line x1="88" y1="80" x2="93" y2="80" stroke="rgba(20,184,166,0.5)" stroke-width="2" />
                    <line x1="70.5" y1="70.5" x2="74" y2="74" stroke="rgba(20,184,166,0.4)" stroke-width="1.5" />
                    <line x1="86" y1="86" x2="89.5" y2="89.5" stroke="rgba(20,184,166,0.4)" stroke-width="1.5" />
                    <line x1="89.5" y1="70.5" x2="86" y2="74" stroke="rgba(20,184,166,0.4)" stroke-width="1.5" />
                    <line x1="74" y1="86" x2="70.5" y2="89.5" stroke="rgba(20,184,166,0.4)" stroke-width="1.5" />
                </g>
                <rect x="92" y="77" width="18" height="6" rx="2" fill="#0d2a3a" stroke="rgba(20,184,166,0.4)" stroke-width="1" />
                <rect x="108" y="68" width="44" height="24" rx="3" fill="#0a1f2e" stroke="#14b8a6" stroke-width="1.2" />
                <line x1="108" y1="76" x2="152" y2="76" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="108" y1="82" x2="152" y2="82" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="108" y1="88" x2="152" y2="88" stroke="rgba(20,184,166,0.35)" stroke-width="1" />
                <line x1="122" y1="68" x2="122" y2="92" stroke="rgba(20,184,166,0.25)" stroke-width="1" />
                <line x1="136" y1="68" x2="136" y2="92" stroke="rgba(20,184,166,0.25)" stroke-width="1" />
                <rect x="108" y="68" width="44" height="24" rx="3" fill="rgba(20,184,166,0.07)" />
                <line x1="80" y1="56" x2="80" y2="36" stroke="rgba(20,184,166,0.5)" stroke-width="1.5" />
                <line x1="80" y1="36" x2="68" y2="24" stroke="rgba(20,184,166,0.4)" stroke-width="1" />
                <line x1="80" y1="36" x2="92" y2="24" stroke="rgba(20,184,166,0.4)" stroke-width="1" />
                <circle class="ant-blink" cx="68" cy="24" r="3" fill="#f59e0b" />
                <circle class="ant-blink" cx="92" cy="24" r="3" fill="#14b8a6" style="animation-delay:0.6s" />
                <rect x="72" y="104" width="16" height="10" rx="2" fill="#061420" stroke="rgba(20,184,166,0.3)" stroke-width="1" />
                <ellipse cx="80" cy="118" rx="6" ry="4" fill="rgba(20,184,166,0.2)">
                    <animate attributeName="ry" values="4;7;3;5;4" dur="0.9s" repeatCount="indefinite" />
                    <animate attributeName="opacity" values="0.3;0.7;0.2;0.5;0.3" dur="0.9s" repeatCount="indefinite" />
                </ellipse>
            </svg>
        </div>

        <div class="badge">
            <div class="badge-dot"></div>
            Pemeliharaan Terjadwal
        </div>

        <h1><strong>Maaf atas ketidaknyamanannya.</strong> <br> Saat ini kami sedang melakukan pemeliharaan rutin untuk meningkatkan kualitas layanan dan keamanan sistem. <br>Kami akan segera kembali dalam waktu dekat. Terima kasih atas kesabaran Anda.</h1>

        <p class="desc">
            <strong>Terima kasih atas kesabaran Anda.</strong> 
        </p>

        <div class="btn-row">
            <a href="javascript:location.reload()" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10" />
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
                </svg>
                Hubungi Admin
            </a>
        </div>

    </div>

    <script>
        const canvas = document.getElementById('cosmos');
        const ctx = canvas.getContext('2d');

        function resize() {
            canvas.width = innerWidth;
            canvas.height = innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        const stars = Array.from({
            length: 200
        }, () => ({
            x: Math.random() * innerWidth,
            y: Math.random() * innerHeight,
            r: Math.random() * 1.5,
            a: 0.2 + Math.random() * 0.7,
            sp: 0.002 + Math.random() * 0.006,
            ph: Math.random() * Math.PI * 2
        }));

        let t = 0;
        (function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                const alpha = s.a * (0.5 + 0.5 * Math.sin(t * s.sp + s.ph));
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(224,242,254,${alpha})`;
                ctx.fill();
            });
            t++;
            requestAnimationFrame(draw);
        })();

        function pad(n) {
            return String(n).padStart(2, '0');
        }
        setInterval(() => {
            const now = new Date();
            document.getElementById('hud-time').textContent =
                'UTC // ' + pad(now.getUTCHours()) + ':' + pad(now.getUTCMinutes()) + ':' + pad(now.getUTCSeconds());
        }, 1000);

        function shoot() {
            const el = document.createElement('div');
            el.className = 'shoot';
            const ang = 15 + Math.random() * 25;
            el.style.cssText = `top:${Math.random()*60}%;left:${Math.random()*80}%;transform:rotate(${ang}deg)`;
            document.body.appendChild(el);
            el.animate([{
                    transform: `rotate(${ang}deg) translate(0,0)`,
                    opacity: 1
                },
                {
                    transform: `rotate(${ang}deg) translate(280px,0)`,
                    opacity: 0
                }
            ], {
                duration: 700,
                easing: 'ease-in'
            }).onfinish = () => el.remove();
        }
        setInterval(shoot, 3000 + Math.random() * 2000);

        ['#14b8a6', '#2dd4bf', '#f59e0b', '#0d9488'].forEach((c, i) => {
            const p = document.createElement('div');
            p.className = 'particle';
            const sz = 2 + Math.random() * 3;
            p.style.cssText = `width:${sz}px;height:${sz}px;background:${c};left:${10+i*22}%;animation-duration:${10+Math.random()*12}s;animation-delay:${Math.random()*8}s;opacity:0.4`;
            document.body.appendChild(p);
        });
    </script>
</body>

</html>