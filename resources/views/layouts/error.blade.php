<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code', 'Error') - StudyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: #f4f6fb; display: grid; place-items: center; }
        .error-card { max-width: 620px; border: 0; border-radius: 1rem; box-shadow: 0 12px 36px rgba(31,42,68,.1); }
        .error-code { font-size: clamp(4rem, 16vw, 7rem); line-height: 1; color: #4f6df5; font-weight: 800; letter-spacing: -.06em; }
        .error-icon { width: 64px; height: 64px; display: grid; place-items: center; margin: 0 auto 1rem; border-radius: 1rem; background: #eef1ff; color: #4f6df5; font-size: 1.7rem; }
    </style>
</head>
<body class="p-3">
    <main class="card error-card w-100">
        <div class="card-body text-center p-4 p-md-5">
            <div class="error-icon"><i class="bi @yield('icon', 'bi-journal-x')"></i></div>
            <div class="error-code mb-3">@yield('code')</div>
            <h1 class="h3 fw-bold mb-2">@yield('title')</h1>
            <p class="text-muted mb-4">@yield('message')</p>
            <a href="{{ url('/') }}" class="btn btn-primary px-4">
                <i class="bi bi-house-door me-1"></i>Kembali ke StudyMate
            </a>
        </div>
        <div class="border-top text-center text-muted small py-3">StudyMate · Temani perjalanan belajarmu</div>
    </main>
</body>
</html>
