<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - 403 Access Denied</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <style>
        :root {
            --accent: #ef4444;
            --bg: #0b0e14;
        }

        body {
            background: var(--bg);
            color: #fff;
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/123163/stars.png') repeat;
            opacity: 0.5;
        }

        .container {
            text-align: center;
            z-index: 10;
            padding: 20px;
        }

        /* Animasi Black Hole */
        .black-hole {
            width: 120px;
            height: 120px;
            background: #000;
            border-radius: 50%;
            margin: 0 auto 30px;
            position: relative;
            box-shadow: 0 0 40px 10px var(--accent), inset 0 0 20px var(--accent);
            animation: pulse 2s infinite alternate;
        }

        @keyframes pulse {
            from {
                transform: scale(1);
                opacity: 0.8;
            }

            to {
                transform: scale(1.1);
                opacity: 1;
            }
        }

        h1 {
            font-size: 6rem;
            margin: 0;
            color: var(--accent);
            text-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
        }

        p {
            color: #94a3b8;
            max-width: 400px;
            margin: 15px auto 30px;
            line-height: 1.6;
        }

        .btn {
            padding: 12px 25px;
            border: 2px solid var(--accent);
            color: var(--accent);
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 0 20px var(--accent);
        }
    </style>
</head>

<body>
    <div class="stars"></div>
    <div class="container">
        <div class="black-hole"></div>
        <h1>403</h1>
        <h2>AREA TERLARANG</h2>
        <p>Sensor keamanan kami mendeteksi Anda mencoba memasuki cakrawala peristiwa tanpa izin. Harap segera kembali ke rute aman.</p>
        <a href="/" class="btn">Kembali ke Pangkalan</a>
    </div>
</body>

</html>