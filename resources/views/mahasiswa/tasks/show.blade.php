@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
    @php
        $statusClasses = ['belum' => 'bg-secondary', 'proses' => 'bg-warning text-dark', 'selesai' => 'bg-success'];
        $statusLabels = ['belum' => 'Belum dikerjakan', 'proses' => 'Dikerjakan', 'selesai' => 'Selesai'];
        $isOverdue = $task->status !== 'selesai' && $task->deadline->isPast();
    @endphp

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <h4 class="fw-bold mb-0">{{ $task->judul }}</h4>
                        <span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }}">{{ $statusLabels[$task->status] ?? ucfirst($task->status) }}</span>
                        @if($isOverdue)
                            <span class="badge bg-danger">Terlambat</span>
                        @endif
                    </div>
                    <p class="text-muted mb-0">Detail tugas dan informasi deadline.</p>
                </div>
                <div class="d-flex gap-2 align-items-start">
                    <a href="{{ route('mahasiswa.tasks.edit', $task) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Ubah</a>
                    <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded bg-light h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-book me-1"></i>Mata Kuliah</div>
                        <div class="fw-semibold">{{ $task->course?->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded bg-light h-100">
                        <div class="small text-muted mb-1"><i class="bi bi-calendar-event me-1"></i>Deadline</div>
                        <div class="fw-semibold {{ $isOverdue ? 'text-danger' : '' }}">{{ $task->deadline->format('l, d F Y - H:i') }}</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 rounded bg-light">
                        <div class="small text-muted mb-1"><i class="bi bi-card-text me-1"></i>Deskripsi</div>
                        <div class="text-break">{!! $task->deskripsi ? nl2br(e($task->deskripsi)) : '<span class="text-muted">Tidak ada deskripsi.</span>' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
