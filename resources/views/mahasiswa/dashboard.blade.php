@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@push('styles')
    <style>
        .student-hero {
            background: linear-gradient(120deg, #26385f 0%, #4f6df5 100%);
            color: #fff;
            border-radius: 1rem;
        }
        .student-stat-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: .85rem;
            font-size: 1.25rem;
        }
    </style>
@endpush

@section('content')
    <section class="student-hero p-4 p-lg-5 mb-4">
        <span class="badge rounded-pill bg-white bg-opacity-25 mb-3">Ruang belajar pribadi</span>
        <h2 class="fw-bold mb-2">Semangat belajar, {{ auth()->user()->name }}!</h2>
        <p class="text-white-50 mb-4">Atur tugas, pantau tenggat, dan selesaikan satu per satu. Kamu pasti bisa.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('mahasiswa.tasks.create') }}" class="btn btn-light text-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
            </a>
            <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-outline-light">
                <i class="bi bi-check2-square me-1"></i>Semua Tugas
            </a>
            <a href="{{ route('mahasiswa.courses.index') }}" class="btn btn-light text-primary fw-semibold">
                <i class="bi bi-book me-1"></i>Lihat Mata Kuliah
            </a>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="student-stat-icon bg-primary-subtle text-primary"><i class="bi bi-journal-text"></i></div>
                <div><div class="text-muted small">Semua Tugas</div><div class="fs-3 fw-bold">{{ $stats['total'] }}</div></div>
            </div></div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="student-stat-icon bg-secondary-subtle text-secondary"><i class="bi bi-circle"></i></div>
                <div><div class="text-muted small">Belum Dikerjakan</div><div class="fs-3 fw-bold">{{ $stats['belum'] }}</div></div>
            </div></div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="student-stat-icon bg-warning-subtle text-warning-emphasis"><i class="bi bi-hourglass-split"></i></div>
                <div><div class="text-muted small">Sedang Dikerjakan</div><div class="fs-3 fw-bold">{{ $stats['proses'] }}</div></div>
            </div></div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="student-stat-icon bg-success-subtle text-success"><i class="bi bi-check2-circle"></i></div>
                <div><div class="text-muted small">Selesai</div><div class="fs-3 fw-bold">{{ $stats['selesai'] }}</div></div>
            </div></div>
        </div>
    </div>

    @if($stats['terlambat'] > 0)
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>Kamu punya <strong>{{ $stats['terlambat'] }} tugas terlambat</strong>. Selesaikan tugas tersebut agar tidak menumpuk.</div>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Deadline Berikutnya</h5>
                    <p class="text-muted small mb-0">Prioritaskan tugas yang tenggatnya paling dekat.</p>
                </div>
                <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-sm btn-outline-primary">Semua tugas</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($upcomingTasks as $task)
                    <div class="list-group-item px-0 py-3 d-flex align-items-center gap-3">
                        <div class="student-stat-icon bg-primary-subtle text-primary flex-shrink-0"><i class="bi bi-journal-bookmark"></i></div>
                        <div class="flex-grow-1 min-width-0">
                            <a href="{{ route('mahasiswa.tasks.show', $task) }}" class="fw-semibold text-truncate text-decoration-none d-block">{{ $task->judul }}</a>
                            <div class="text-muted small">{{ $task->course?->nama ?? 'Mata kuliah tidak tersedia' }}</div>
                        </div>
                        <div class="text-end text-nowrap">
                            <div class="small fw-semibold">{{ $task->deadline->format('d M Y') }}</div>
                            <div class="text-muted small">{{ $task->deadline->format('H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="student-stat-icon bg-success-subtle text-success mx-auto mb-3"><i class="bi bi-check2"></i></div>
                        <h6 class="fw-bold">Kamu sudah mengejar semua deadline!</h6>
                        <p class="text-muted small mb-3">Nikmati waktu istirahatmu atau tambahkan tugas berikutnya.</p>
                        <a href="{{ route('mahasiswa.tasks.create') }}" class="btn btn-sm btn-primary">Tambah tugas</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection