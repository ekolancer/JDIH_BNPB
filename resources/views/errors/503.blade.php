<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - Maintenance</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <style>
        :root {
            --accent: #f59e0b;
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

        .satellite {
            font-size: 5rem;
            margin-bottom: 20px;
            display: inline-block;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 6rem;
            margin: 0;
            color: var(--accent);
        }

        p {
            color: #94a3b8;
            max-width: 450px;
            margin: 15px auto 30px;
        }

        .status-badge {
            background: rgba(245, 158, 11, 0.1);
            border: 1px dashed var(--accent);
            padding: 10px 20px;
            border-radius: 5px;
            color: var(--accent);
            display: inline-block;
            font-family: monospace;
        }
    </style>
</head>

<body>
    <div class="stars"></div>
    <div class="container">
        <div class="satellite">🛰️</div>
        <h1>503</h1>
        <h2>PEMELIHARAAN STASIUN</h2>
        <p>Kami sedang melakukan kalibrasi ulang pada sistem inti. Jangan khawatir, kru kami sedang bekerja keras di luar sana.</p>
        <div class="status-badge">STATUS: REBOOTING_SYSTEM_CORE...</div>
    </div>
</body>

</html>