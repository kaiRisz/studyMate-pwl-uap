<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - StudyMate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        .sidebar { width: 250px; min-height: 100vh; background: #1f2a44; }
        .sidebar .brand { color: #fff; font-weight: 700; font-size: 1.3rem; padding: 1.2rem 1.5rem; }
        .sidebar .nav-link { color: #b8c1d9; padding: .75rem 1.5rem; border-radius: 0; }
        .sidebar .nav-link:hover { color: #fff; background: #2b3a5e; }
        .sidebar .nav-link.active { color: #fff; background: #4f6df5; }
        .sidebar .menu-title { color: #7d89a8; font-size: .75rem; padding: 1rem 1.5rem .3rem; text-transform: uppercase; }
        .content { flex: 1; min-width: 0; }
        .card { border: 0; box-shadow: 0 2px 8px rgba(0,0,0,.06); border-radius: .75rem; }
        @media (max-width: 768px) { .sidebar { display: none; } }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <aside class="sidebar">
        <div class="brand"><i class="bi bi-journal-bookmark-fill me-2"></i>StudyMate</div>

        @if(auth()->user()->role === 'admin')
            <div class="menu-title">Menu Admin</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                    <i class="bi bi-book me-2"></i>Mata Kuliah</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people me-2"></i>Mahasiswa</a>
                <a class="nav-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}" href="{{ route('admin.tasks.index') }}">
                    <i class="bi bi-list-check me-2"></i>Semua Tugas</a>
            </nav>
        @else
            <div class="menu-title">Menu Mahasiswa</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}" href="{{ route('mahasiswa.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                <a class="nav-link {{ request()->routeIs('mahasiswa.courses.*') ? 'active' : '' }}" href="{{ route('mahasiswa.courses.index') }}">
                    <i class="bi bi-book me-2"></i>Mata Kuliah</a>
                <a class="nav-link {{ request()->routeIs('mahasiswa.tasks.*') ? 'active' : '' }}" href="{{ route('mahasiswa.tasks.index') }}">
                    <i class="bi bi-check2-square me-2"></i>Tugas Saya</a>
            </nav>
        @endif
    </aside>

    <div class="content">
        <nav class="navbar bg-white shadow-sm px-4">
            <span class="fw-semibold">@yield('title', 'Dashboard')</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    <span class="badge bg-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                </form>
            </div>
        </nav>

        <main class="p-4">
            @include('partials.alerts')
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi hapus global: pakai class "form-delete" pada form hapus
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form.matches('.form-delete')) return;
        e.preventDefault();
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: 'Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@stack('scripts')
</body>
</html>