<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>JDIH - 404 Page Not Found</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css" rel="stylesheet') }}">


    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/detail.css') }}" rel="stylesheet">
    <style>
        /* From Uiverse.io by zjssun */
        .button {
            position: relative;
            padding: 10px 22px;
            border-radius: 6px;
            border: none;
            color: #fff;
            cursor: pointer;
            background-color: #ef8200;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .button:active {
            transform: scale(0.96);
        }

        .button:before,
        .button:after {
            position: absolute;
            content: "";
            width: 150%;
            left: 50%;
            height: 100%;
            transform: translateX(-50%);
            z-index: -1000;
            background-repeat: no-repeat;
        }

        .button:hover:before {
            top: -70%;
            background-image: radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, transparent 20%, #ef8200 20%, transparent 30%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, transparent 10%, #ef8200 15%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%);
            background-size: 10% 10%, 20% 20%, 15% 15%, 20% 20%, 18% 18%, 10% 10%, 15% 15%,
                10% 10%, 18% 18%;
            background-position: 50% 120%;
            animation: greentopBubbles 0.6s ease;
        }

        @keyframes greentopBubbles {
            0% {
                background-position: 5% 90%, 10% 90%, 10% 90%, 15% 90%, 25% 90%, 25% 90%,
                    40% 90%, 55% 90%, 70% 90%;
            }

            50% {
                background-position: 0% 80%, 0% 20%, 10% 40%, 20% 0%, 30% 30%, 22% 50%,
                    50% 50%, 65% 20%, 90% 30%;
            }

            100% {
                background-position: 0% 70%, 0% 10%, 10% 30%, 20% -10%, 30% 20%, 22% 40%,
                    50% 40%, 65% 10%, 90% 20%;
                background-size: 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%;
            }
        }

        .button:hover::after {
            bottom: -70%;
            background-image: radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, transparent 10%, #ef8200 15%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%),
                radial-gradient(circle, #ef8200 20%, transparent 20%);
            background-size: 15% 15%, 20% 20%, 18% 18%, 20% 20%, 15% 15%, 20% 20%, 18% 18%;
            background-position: 50% 0%;
            animation: greenbottomBubbles 0.6s ease;
        }

        @keyframes greenbottomBubbles {
            0% {
                background-position: 10% -10%, 30% 10%, 55% -10%, 70% -10%, 85% -10%,
                    70% -10%, 70% 0%;
            }

            50% {
                background-position: 0% 80%, 20% 80%, 45% 60%, 60% 100%, 75% 70%, 95% 60%,
                    105% 0%;
            }

            100% {
                background-position: 0% 90%, 20% 90%, 45% 70%, 60% 110%, 75% 80%, 95% 70%,
                    110% 10%;
                background-size: 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%;
            }
        }
    </style>
</head>

<body>
    <!-- Content 404 Start -->
    <div class="text-center py-5">
        <img src="{{ asset('img/404.png') }}" alt="404 Error" class="img-fluid" style="max-width: 30%; height: 20%;">
        <h2 class="mt-4">Oops! Halaman Tidak Ditemukan</h2>
        <p class="mb-4">Maaf, halaman yang Anda cari tidak ditemukan. Coba kembali ke halaman utama.</p>
        <a href="/" class="button">Kembali ke Halaman Utama</a>
    </div>
    <!-- Content 404 End -->
</body>

</html>