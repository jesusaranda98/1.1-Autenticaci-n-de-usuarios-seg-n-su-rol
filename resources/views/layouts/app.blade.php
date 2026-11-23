<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autenticación por Roles')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #312e81 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .auth-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        }
        .code-input {
            font-size: 1.8rem;
            letter-spacing: 0.65rem;
            font-weight: 700;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4 px-3">
    <div class="container" style="max-width: 960px;">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>