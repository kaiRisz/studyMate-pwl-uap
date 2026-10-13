<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - StudyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #4f6df5, #1f2a44); }
        .auth-card { max-width: 440px; border: 0; border-radius: 1rem; }
    </style>
</head>
<body class="d-flex align-items-center py-4">
<div class="container">
    <div class="card auth-card shadow-lg mx-auto">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-journal-bookmark-fill fs-1 text-primary"></i>
                <h3 class="fw-bold mb-0">StudyMate</h3>
                <small class="text-muted">Sistem Manajemen Belajar Mahasiswa</small>
            </div>
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>