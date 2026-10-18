@extends('layouts.app')
@section('title', 'Dashboard Admin')

@push('styles')
    <style>
        .dashboard-hero {
            background: linear-gradient(120deg, #26385f 0%, #4f6df5 100%);
            color: #fff;
            border-radius: 1rem;
            overflow: hidden;
            position: relative;
        }
        .dashboard-hero::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: 5%;
            top: -100px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 50%;
            box-shadow: 0 0 0 28px rgba(255,255,255,.05), 0 0 0 58px rgba(255,255,255,.04);
        }
        .stat-card { height: 100%; }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: .85rem;
            font-size: 1.35rem;
        }
        .dashboard-table th { color: #718096; font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; }
    </style>
@endpush

@section('content')
    <section class="dashboard-hero p-4 p-lg-5 mb-4">
        <div class="position-relative" style="z-index: 1;">
            <span class="badge rounded-pill bg-white bg-opacity-25 mb-3">Pusat kendali StudyMate</span>
            <h2 class="fw-bold mb-2">Halo, {{ auth()->user()->name }}!</h2>
            <p class="mb-4 text-white-50">Pantau aktivitas belajar dan kelola data akademik dari satu tempat.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.courses.create') }}" class="btn btn-light text-primary fw-semibold">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Mata Kuliah
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-people me-1"></i>Lihat Mahasiswa
                </a>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.courses.index') }}" class="text-decoration-none text-reset">
                <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-book"></i></div>
                    <div><div class="text-muted small">Mata Kuliah</div><div class="fs-3 fw-bold">{{ $stats['courses'] }}</div></div>
                </div></div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-reset">
                <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-people"></i></div>
                    <div><div class="text-muted small">Mahasiswa</div><div class="fs-3 fw-bold">{{ $stats['students'] }}</div></div>
                </div></div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none text-reset">
                <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-journal-check"></i></div>
                    <div><div class="text-muted small">Total Tugas</div><div class="fs-3 fw-bold">{{ $stats['tasks'] }}</div></div>
                </div></div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none text-reset">
                <div class="card stat-card"><div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-alarm"></i></div>
                    <div><div class="text-muted small">Tugas Terlambat</div><div class="fs-3 fw-bold">{{ $stats['overdueTasks'] }}</div></div>
                </div></div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Tugas Terbaru</h5>
                    <p class="text-muted small mb-0">Aktivitas tugas yang baru ditambahkan.</p>
                </div>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table dashboard-table align-middle mb-0">
                    <thead><tr><th>Tugas</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Deadline</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($latestTasks as $task)
                            @php
                                $statusClasses = ['belum' => 'bg-secondary', 'proses' => 'bg-warning text-dark', 'selesai' => 'bg-success'];
                                $statusLabels = ['belum' => 'Belum dikerjakan', 'proses' => 'Dikerjakan', 'selesai' => 'Selesai'];
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $task->judul }}</td>
                                <td>{{ $task->user?->name ?? '-' }}</td>
                                <td>{{ $task->course?->nama ?? '-' }}</td>
                                <td class="text-nowrap">{{ $task->deadline?->format('d M Y, H:i') ?? '-' }}</td>
                                <td><span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }}">{{ $statusLabels[$task->status] ?? ucfirst($task->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada tugas. Data tugas mahasiswa akan muncul di sini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection