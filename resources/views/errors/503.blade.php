<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 — Service Unavailable</title>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --void: #030508;
            --deep: #060c18;
            --blue-core: #0ea5e9;
            --blue-dim: #0369a1;
            --blue-glow: #38bdf8;
            --teal: #14b8a6;
            --warn: #f59e0b;
            --ice: #e0f2fe;
            --muted: #475569;
            --text: #94a3b8;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            background: var(--void);
            color: var(--ice);
            font-family: 'Space Mono', monospace;
            overflow: hidden;
        }

        /* Glitch overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg,
                    transparent,
                    transparent 2px,
                    rgba(14, 165, 233, 0.012) 2px,
                    rgba(14, 165, 233, 0.012) 4px);
            z-index: 200;
            pointer-events: none;
            animation: glitchScan 10s linear infinite;
        }

        @keyframes glitchScan {
            0% {
                opacity: 1;
            }

            49% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
                transform: translateY(-3px);
            }

            51% {
                opacity: 1;
                transform: translateY(0);
            }

            89% {
                opacity: 1;
            }

            90% {
                opacity: 0.8;
                transform: translateX(2px);
            }

            91% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Canvas */
        #bg {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        /* Nebulas — blue/teal theme */
        .nebula {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 1;
        }

        .nb1 {
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse, rgba(14, 165, 233, 0.15), transparent 70%);
            top: -200px;
            left: -200px;
            animation: nb1d 22s ease-in-out infinite alternate;
        }

        .nb2 {
            width: 600px;
            height: 500px;
            background: radial-gradient(ellipse, rgba(20, 184, 166, 0.12), transparent 70%);
            bottom: -150px;
            right: -100px;
            animation: nb2d 28s ease-in-out infinite alternate;
        }

        .nb3 {
            width: 400px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(56, 189, 248, 0.08), transparent 70%);
            top: 50%;
            left: 50%;
            animation: nb3d 16s ease-in-out infinite alternate;
        }

        @keyframes nb1d {
            to {
                transform: translate(70px, 60px);
            }
        }

        @keyframes nb2d {
            to {
                transform: translate(-60px, -50px);
            }
        }

        @keyframes nb3d {
            to {
                transform: translate(-40px, 30px);
            }
        }

        /* Scene */
        .scene {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            gap: 70px;
            padding: 40px;
        }

        /* Space station illustration */
        .station-wrap {
            position: relative;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Slow tumble of the broken station */
        .station-svg {
            width: 300px;
            animation: tumbleSlow 12s linear infinite;
            filter:
                drop-shadow(0 0 20px rgba(14, 165, 233, 0.3)) drop-shadow(0 0 40px rgba(14, 165, 233, 0.1));
        }

        @keyframes tumbleSlow {
            0% {
                transform: rotate(0deg) translateY(0);
            }

            25% {
                transform: rotate(4deg) translateY(-8px);
            }

            50% {
                transform: rotate(0deg) translateY(-14px);
            }

            75% {
                transform: rotate(-4deg) translateY(-8px);
            }

            100% {
                transform: rotate(0deg) translateY(0);
            }
        }

        /* Debris particles floating around */
        .debris {
            position: absolute;
            border-radius: 2px;
            background: rgba(14, 165, 233, 0.5);
            animation: debrisFloat linear infinite;
        }

        @keyframes debrisFloat {
            0% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 0.8;
            }

            90% {
                opacity: 0.6;
            }

            100% {
                transform: translate(var(--tx), var(--ty)) rotate(var(--rot));
                opacity: 0;
            }
        }

        /* Electric spark effect */
        .spark {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--warn);
            box-shadow: 0 0 6px var(--warn);
            animation: sparkFly linear infinite;
        }

        @keyframes sparkFly {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 1;
            }

            100% {
                transform: translate(var(--sx), var(--sy)) scale(0);
                opacity: 0;
            }
        }

        /* Distress beacon ring */
        .beacon {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px solid rgba(245, 158, 11, 0.5);
            transform: translate(-50%, -50%);
            animation: beaconPulse 3s ease-out infinite;
        }

        .beacon:nth-child(2) {
            animation-delay: 1s;
        }

        .beacon:nth-child(3) {
            animation-delay: 2s;
        }

        @keyframes beaconPulse {
            0% {
                width: 20px;
                height: 20px;
                opacity: 0.8;
            }

            100% {
                width: 320px;
                height: 320px;
                opacity: 0;
            }
        }

        /* Right panel */
        .info-panel {
            max-width: 480px;
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(14, 165, 233, 0.08);
            border: 1px solid rgba(14, 165, 233, 0.3);
            border-radius: 100px;
            padding: 6px 18px 6px 12px;
            font-size: 11px;
            letter-spacing: 3px;
            color: var(--blue-glow);
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        .pill-anim {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--warn);
            animation: pillBlink 1.5s ease-in-out infinite;
        }

        @keyframes pillBlink {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
                background: var(--warn);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(245, 158, 11, 0);
                background: #fcd34d;
            }
        }

        /* Error number */
        .err-num {
            font-family: 'Russo One', sans-serif;
            font-size: clamp(80px, 12vw, 128px);
            line-height: 0.9;
            position: relative;
            margin-bottom: 10px;
        }

        .err-num span {
            display: inline-block;
            color: transparent;
            -webkit-text-stroke: 2px var(--blue-core);
            text-stroke: 2px var(--blue-core);
            filter: drop-shadow(0 0 20px rgba(14, 165, 233, 0.5));
            animation: numGlitch 7s ease-in-out infinite;
        }

        .err-num .solid {
            position: absolute;
            top: 0;
            left: 0;
            -webkit-text-stroke: 0;
            color: var(--blue-core);
            opacity: 0.15;
        }

        @keyframes numGlitch {

            0%,
            80%,
            100% {
                transform: translate(0);
                filter: drop-shadow(0 0 20px rgba(14, 165, 233, 0.5));
            }

            82% {
                transform: translate(-4px, 2px);
                filter: drop-shadow(0 0 30px rgba(245, 158, 11, 0.6));
                color: var(--warn);
                -webkit-text-stroke-color: var(--warn);
            }

            84% {
                transform: translate(4px, -2px);
            }

            86% {
                transform: translate(0);
                filter: drop-shadow(0 0 20px rgba(14, 165, 233, 0.5));
                color: var(--blue-core);
                -webkit-text-stroke-color: var(--blue-core);
            }
        }

        .err-sub {
            font-size: 12px;
            letter-spacing: 5px;
            color: rgba(14, 165, 233, 0.5);
            text-transform: uppercase;
            margin-bottom: 26px;
        }

        /* Description */
        .desc {
            font-family: 'Space Mono', monospace;
            font-size: 14px;
            color: var(--text);
            line-height: 2;
            margin-bottom: 28px;
            max-width: 420px;
        }

        .desc strong {
            color: var(--ice);
        }

        /* System status panel */
        .sys-panel {
            background: rgba(14, 165, 233, 0.04);
            border: 1px solid rgba(14, 165, 233, 0.15);
            border-radius: 6px;
            padding: 18px 22px;
            margin-bottom: 32px;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }

        .sys-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--blue-core), transparent);
            animation: scanLine 3s linear infinite;
        }

        @keyframes scanLine {
            0% {
                transform: scaleX(0);
                opacity: 0;
            }

            50% {
                transform: scaleX(1);
                opacity: 1;
            }

            100% {
                transform: scaleX(0);
                opacity: 0;
            }
        }

        .sys-title {
            font-size: 10px;
            letter-spacing: 3px;
            color: rgba(14, 165, 233, 0.4);
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .sys-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid rgba(14, 165, 233, 0.06);
            font-size: 12px;
        }

        .sys-row:last-child {
            border-bottom: none;
        }

        .sys-key {
            color: var(--muted);
        }

        .sys-val {
            font-weight: 700;
        }

        .v-off {
            color: #f87171;
        }

        .v-warn {
            color: var(--warn);
            animation: valBlink 2s step-end infinite;
        }

        .v-ok {
            color: #4ade80;
        }

        @keyframes valBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* Countdown */
        .countdown-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .countdown-label {
            font-size: 11px;
            letter-spacing: 2px;
            color: rgba(14, 165, 233, 0.4);
            text-transform: uppercase;
        }

        .countdown {
            font-size: 13px;
            color: var(--warn);
            letter-spacing: 2px;
        }

        /* Progress bar */
        .prog-bar {
            height: 3px;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 28px;
            max-width: 420px;
        }

        .prog-fill {
            height: 100%;
            background: linear-gradient(to right, var(--teal), var(--blue-core), var(--blue-glow));
            border-radius: 2px;
            animation: progAnim 8s ease-in-out infinite alternate;
            box-shadow: 0 0 8px var(--blue-core);
        }

        @keyframes progAnim {
            0% {
                width: 8%;
            }

            100% {
                width: 68%;
            }
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 14px;
        }

        .btn {
            padding: 13px 26px;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            letter-spacing: 2px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.06) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .btn:hover::before {
            transform: translateX(100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue-dim), var(--blue-core));
            color: white;
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.3), 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .btn-primary:hover {
            box-shadow: 0 0 35px rgba(14, 165, 233, 0.6), 0 4px 16px rgba(0, 0, 0, 0.5);
            transform: translateY(-2px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--blue-glow);
            border: 1px solid rgba(14, 165, 233, 0.25);
        }

        .btn-ghost:hover {
            background: rgba(14, 165, 233, 0.08);
            border-color: var(--blue-core);
            transform: translateY(-2px);
        }

        /* HUD */
        .hud {
            position: fixed;
            font-size: 10px;
            letter-spacing: 2px;
            color: rgba(14, 165, 233, 0.2);
            pointer-events: none;
            z-index: 50;
            line-height: 1.9;
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

        /* Corner crosshairs */
        .cross {
            position: fixed;
            z-index: 50;
            pointer-events: none;
            opacity: 0.25;
        }

        .cross::before,
        .cross::after {
            content: '';
            position: absolute;
            background: var(--blue-core);
        }

        .cross::before {
            width: 20px;
            height: 1px;
            top: 4px;
            left: 0;
        }

        .cross::after {
            width: 1px;
            height: 20px;
            top: 0;
            left: 4px;
        }

        .cross-tl {
            top: 14px;
            left: 14px;
        }

        .cross-tr {
            top: 14px;
            right: 14px;
            transform: scaleX(-1);
        }

        .cross-bl {
            bottom: 14px;
            left: 14px;
            transform: scaleY(-1);
        }

        .cross-br {
            bottom: 14px;
            right: 14px;
            transform: scale(-1);
        }
    </style>
</head>

<body>

    <canvas id="bg"></canvas>
    <div class="nebula nb1"></div>
    <div class="nebula nb2"></div>
    <div class="nebula nb3"></div>

    <!-- Crosshairs -->
    <div class="cross cross-tl"></div>
    <div class="cross cross-tr"></div>
    <div class="cross cross-bl"></div>
    <div class="cross cross-br"></div>

    <!-- HUD -->
    <div class="hud hud-tl">
        <div>SYSTEM // JDIH-SERVER</div>
        <div>NODE // ALPHA-7</div>
        <div id="ts">UTC // --:--:--</div>
    </div>
    <div class="hud hud-tr">
        <div>STATUS // UNAVAILABLE</div>
        <div>UPTIME // 00:00:00</div>
        <div id="load">LOAD // ---</div>
    </div>
    <div class="hud hud-bl">
        <div>ERR // HTTP 503</div>
        <div>RETRY // SCHEDULED</div>
    </div>
    <div class="hud hud-br">
        <div>BACKUP // OFFLINE</div>
        <div>ETA // CALCULATING</div>
    </div>

    <div class="scene">

        <!-- Broken space station -->
        <div class="station-wrap">

            <!-- Distress beacon -->
            <div class="beacon"></div>
            <div class="beacon"></div>
            <div class="beacon"></div>

            <!-- Space station SVG -->
            <svg class="station-svg" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">

                <!-- Tilt the whole station slightly (damaged) -->
                <g transform="rotate(-12, 150, 150)">

                    <!-- Central hub -->
                    <circle cx="150" cy="150" r="45" fill="#0c2a3d" stroke="#0ea5e9" stroke-width="1.5" />
                    <circle cx="150" cy="150" r="35" fill="#071e2d" stroke="rgba(14,165,233,0.4)" stroke-width="1" />
                    <!-- Hub detail rings -->
                    <circle cx="150" cy="150" r="20" fill="none" stroke="rgba(14,165,233,0.2)" stroke-width="1" stroke-dasharray="4 4" />
                    <!-- Hub window -->
                    <circle cx="150" cy="150" r="12" fill="#1a4a6e" stroke="rgba(56,189,248,0.6)" stroke-width="1" />
                    <circle cx="147" cy="147" r="5" fill="rgba(56,189,248,0.3)" />

                    <!-- Left solar panel arm -->
                    <rect x="30" y="145" width="75" height="10" rx="3" fill="#0c2a3d" stroke="#0ea5e9" stroke-width="1" />
                    <!-- Left panel array (intact) -->
                    <rect x="18" y="120" width="30" height="60" rx="2" fill="#071e2d" stroke="rgba(14,165,233,0.6)" stroke-width="1" />
                    <line x1="18" y1="135" x2="48" y2="135" stroke="rgba(14,165,233,0.4)" stroke-width="1" />
                    <line x1="18" y1="150" x2="48" y2="150" stroke="rgba(14,165,233,0.4)" stroke-width="1" />
                    <line x1="18" y1="165" x2="48" y2="165" stroke="rgba(14,165,233,0.4)" stroke-width="1" />
                    <line x1="33" y1="120" x2="33" y2="180" stroke="rgba(14,165,233,0.3)" stroke-width="1" />
                    <!-- Panel glow -->
                    <rect x="18" y="120" width="30" height="60" rx="2" fill="rgba(14,165,233,0.06)" />

                    <!-- Right solar panel arm (BROKEN/bent) -->
                    <path d="M 195 148 L 252 138 L 258 148 L 252 158 L 195 158 Z"
                        fill="#0c2a3d" stroke="#0ea5e9" stroke-width="1" opacity="0.8" />
                    <!-- Right panel (cracked, tilted) -->
                    <g transform="rotate(25, 268, 138)">
                        <rect x="250" y="108" width="28" height="58" rx="2"
                            fill="#071e2d" stroke="rgba(14,165,233,0.4)" stroke-width="1" opacity="0.7" />
                        <!-- Crack lines -->
                        <line x1="250" y1="108" x2="278" y2="166" stroke="rgba(245,158,11,0.5)" stroke-width="1.5" />
                        <line x1="260" y1="108" x2="252" y2="140" stroke="rgba(245,158,11,0.3)" stroke-width="1" />
                    </g>

                    <!-- Top module -->
                    <rect x="135" y="68" width="30" height="40" rx="4" fill="#0c2a3d" stroke="rgba(14,165,233,0.5)" stroke-width="1" />
                    <rect x="140" y="74" width="20" height="12" rx="2" fill="#1a3a5c" stroke="rgba(56,189,248,0.4)" stroke-width="1" />
                    <!-- Antenna (bent) -->
                    <path d="M 150 68 L 148 50 L 158 40" stroke="rgba(14,165,233,0.6)" stroke-width="1.5" fill="none" />
                    <circle cx="158" cy="40" r="3" fill="var(--warn)" opacity="0.8">
                        <animate attributeName="opacity" values="0.8;0.1;0.8" dur="1.2s" repeatCount="indefinite" />
                    </circle>

                    <!-- Bottom thruster module -->
                    <rect x="132" y="192" width="36" height="30" rx="4" fill="#0c2a3d" stroke="rgba(14,165,233,0.4)" stroke-width="1" />
                    <!-- Thruster exhaust (flickering) -->
                    <ellipse cx="150" cy="228" rx="10" ry="5" fill="rgba(56,189,248,0.2)">
                        <animate attributeName="ry" values="5;8;3;6;5" dur="0.8s" repeatCount="indefinite" />
                        <animate attributeName="opacity" values="0.3;0.6;0.2;0.5;0.3" dur="0.8s" repeatCount="indefinite" />
                    </ellipse>

                    <!-- Damage sparks -->
                    <circle cx="220" cy="140" r="3" fill="var(--warn)">
                        <animate attributeName="opacity" values="1;0;1;0;0;1" dur="0.6s" repeatCount="indefinite" />
                        <animate attributeName="r" values="3;5;2;4;3" dur="0.6s" repeatCount="indefinite" />
                    </circle>
                    <circle cx="200" cy="155" r="2" fill="#fbbf24">
                        <animate attributeName="opacity" values="0;1;0;0;1;0" dur="0.9s" repeatCount="indefinite" />
                    </circle>

                    <!-- Orbit ring around station -->
                    <ellipse cx="150" cy="150" rx="80" ry="25"
                        stroke="rgba(14,165,233,0.12)" stroke-width="1"
                        fill="none" stroke-dasharray="6 4"
                        transform="rotate(-20, 150, 150)" />

                </g>
            </svg>

            <!-- Floating debris -->
            <div class="debris" style="width:6px;height:3px;top:30%;left:20%;--tx:40px;--ty:-30px;--rot:90deg;animation-duration:4s;animation-delay:0s;"></div>
            <div class="debris" style="width:4px;height:4px;top:60%;left:75%;--tx:-35px;--ty:25px;--rot:180deg;animation-duration:5s;animation-delay:1s;border-radius:50%;"></div>
            <div class="debris" style="width:8px;height:2px;top:20%;left:65%;--tx:20px;--ty:40px;--rot:120deg;animation-duration:6s;animation-delay:2s;"></div>
            <div class="debris" style="width:3px;height:3px;top:75%;left:30%;--tx:-20px;--ty:-35px;--rot:60deg;animation-duration:4.5s;animation-delay:0.5s;border-radius:50%;"></div>
            <div class="debris" style="width:5px;height:2px;top:40%;left:80%;--tx:-30px;--ty:-20px;--rot:200deg;animation-duration:7s;animation-delay:1.5s;"></div>

            <!-- Sparks -->
            <div class="spark" style="top:42%;left:63%;--sx:20px;--sy:-15px;animation-duration:0.8s;animation-delay:0s;"></div>
            <div class="spark" style="top:45%;left:65%;--sx:-15px;--sy:20px;animation-duration:1s;animation-delay:0.3s;background:#38bdf8;box-shadow:0 0 6px #38bdf8;"></div>
            <div class="spark" style="top:48%;left:60%;--sx:18px;--sy:12px;animation-duration:0.7s;animation-delay:0.6s;"></div>
        </div>

        <!-- Info panel -->
        <div class="info-panel">

            <div class="status-pill">
                <div class="pill-anim"></div>
                SERVER TIDAK TERSEDIA
            </div>

            <div class="err-num">
                <span>503</span>
                <span class="solid" aria-hidden="true">503</span>
            </div>
            <div class="err-sub">SERVICE UNAVAILABLE</div>

            <p class="desc">
                Stasiun server galaksi kami mengalami <strong>kendala sistem</strong>.<br>
                Tim insinyur luar angkasa sedang bekerja keras untuk memulihkan layanan.<br>
                Mohon coba kembali dalam beberapa saat.
            </p>

            <div class="prog-bar">
                <div class="prog-fill"></div>
            </div>

            <div class="sys-panel">
                <div class="sys-title">⚡ Diagnostik Sistem</div>
                <div class="sys-row">
                    <span class="sys-key">Web Server</span>
                    <span class="sys-val v-off">OFFLINE</span>
                </div>
                <div class="sys-row">
                    <span class="sys-key">Database</span>
                    <span class="sys-val v-warn">RECOVERING</span>
                </div>
                <div class="sys-row">
                    <span class="sys-key">Cache Layer</span>
                    <span class="sys-val v-off">OFFLINE</span>
                </div>
                <div class="sys-row">
                    <span class="sys-key">Backup Node</span>
                    <span class="sys-val v-warn">STANDBY</span>
                </div>
                <div class="sys-row">
                    <span class="sys-key">CDN</span>
                    <span class="sys-val v-ok">ONLINE</span>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Star canvas
        const canvas = document.getElementById('bg');
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
            r: Math.random() * 1.6,
            a: 0.2 + Math.random() * 0.7,
            sp: 0.002 + Math.random() * 0.007,
            ph: Math.random() * Math.PI * 2,
            blue: Math.random() > 0.7
        }));

        let t = 0;
        (function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            stars.forEach(s => {
                const alpha = s.a * (0.5 + 0.5 * Math.sin(t * s.sp + s.ph));
                const r = s.blue ? `rgba(56,189,248,${alpha})` : `rgba(224,242,254,${alpha * 0.7})`;
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = r;
                ctx.fill();
            });
            t++;
            requestAnimationFrame(draw);
        })();

        // HUD clock
        setInterval(() => {
            const now = new Date();
            document.getElementById('ts').textContent =
                `UTC // ${String(now.getUTCHours()).padStart(2,'0')}:${String(now.getUTCMinutes()).padStart(2,'0')}:${String(now.getUTCSeconds()).padStart(2,'0')}`;
            document.getElementById('load').textContent =
                `LOAD // ${(Math.random() * 0.3 + 0.05).toFixed(2)}`;
        }, 1000);

        // Countdown timer (fake — counts down from 8:30, then resets)
        let total = 510;
        setInterval(() => {
            total = total > 0 ? total - 1 : 510;
            const m = String(Math.floor(total / 60)).padStart(2, '0');
            const s = String(total % 60).padStart(2, '0');
            document.getElementById('cd').textContent = `${m}:${s}`;
        }, 1000);
    </script>
</body>

</html>