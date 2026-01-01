<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dalam Pengembangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: none;
        }

        .icon {
            font-size: 4rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 0.8;
            }
        }

        .btn-home {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }

        .btn-home:hover {
            background-color: #e0a800;
        }
    </style>
</head>

<body>

    <div class="container text-center">
        <div class="card p-5 shadow-lg">
            <div class="mb-4">
                <i class="bi bi-tools icon text-warning"></i>
            </div>
            <h1 class="fw-bold text-white mb-3">Halaman Ini Sedang Dalam Pengembangan</h1>
            <p class="text-white">By Jendral </p>
        </div>
    </div>

</body>

</html>
