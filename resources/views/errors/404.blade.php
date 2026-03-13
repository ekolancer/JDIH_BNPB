<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - 404 Page Not Found</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <style>
        :root {
            --primary: #818cf8;
            --bg: #0b0e14;
        }

        body {
            background: var(--bg);
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Animasi Bintang */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/123163/stars.png') repeat;
            z-index: 0;
        }

        .container {
            text-align: center;
            z-index: 10;
            padding: 20px;
        }

        /* Ikon Sedih di atas 404 */
        .sad-svg {
            width: 60px;
            height: 60px;
            fill: var(--primary);
            margin-bottom: -10px;
            animation: float 3s ease-in-out infinite;
        }

        /* Bulan dan Roket */
        .space-scene {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }

        .moon {
            width: 100px;
            height: 100px;
            background: #e2e8f0;
            border-radius: 50%;
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
            margin: 20px auto;
        }

        .rocket {
            position: absolute;
            font-size: 3rem;
            top: 0;
            left: 0;
            animation: rocket-fly 4s ease-in-out infinite;
        }

        @keyframes rocket-fly {
            0% {
                transform: translate(-20px, 80px) rotate(-45deg);
            }

            50% {
                transform: translate(100px, -20px) rotate(-45deg);
            }

            100% {
                transform: translate(-20px, 80px) rotate(-45deg);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        h1 {
            font-size: 7rem;
            margin: 0;
            color: var(--primary);
            font-weight: 900;
            text-shadow: 0 0 20px rgba(129, 140, 248, 0.5);
        }

        p {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-top: 10px;
        }

        /* Progress Bar */
        .progress-box {
            width: 100%;
            max-width: 300px;
            height: 6px;
            background: #1e293b;
            border-radius: 10px;
            margin: 25px auto;
            overflow: hidden;
        }

        .bar {
            width: 0%;
            height: 100%;
            background: var(--primary);
            box-shadow: 0 0 10px var(--primary);
            animation: fill 10s linear forwards;
        }

        @keyframes fill {
            to {
                width: 100%;
            }
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px var(--primary);
        }
    </style>
</head>

<body>
    <div class="stars"></div>
    <div class="container">
        <svg class="sad-svg" viewBox="0 0 24 24">
            <path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-3.5-9.5c.83 0 1.5-.67 1.5-1.5S9.33 7.5 8.5 7.5 7 8.17 7 9s.67 1.5 1.5 1.5zm7 0c.83 0 1.5-.67 1.5-1.5S16.17 7.5 15.33 7.5s-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM12 14c2.33 0 4.31 1.46 5.11 3.5H6.89c.8-2.04 2.78-3.5 5.11-3.5z" />
        </svg>

        <h1>404</h1>

        <div class="space-scene">
            <div class="moon"></div>
            <div class="rocket">🚀</div>
        </div>

        <p>Waduh! Koordinat salah. Anda tersesat di luar angkasa.</p>

        <div class="progress-box">
            <div class="bar"></div>
        </div>
        <p style="font-size: 0.9rem; color: #64748b;">Mendarat di Bumi dalam <span id="timer">10</span> detik...</p>

        <a href="/" class="btn">Kembali Sekarang</a>
    </div>

    <script>
        let count = 10;
        const timer = document.getElementById('timer');
        setInterval(() => {
            count--;
            if (count >= 0) timer.innerText = count;
            if (count === 0) window.location.href = "/";
        }, 1000);
    </script>
</body>

</html>