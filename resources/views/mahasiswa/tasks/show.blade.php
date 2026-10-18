@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('mahasiswa.tasks.index') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Tugas Saya
        </a>
        <h2 class="fw-bold mt-3 mb-1">Detail Tugas</h2>
        <p class="text-muted mb-0">Lihat informasi lengkap tugas kuliahmu.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-2">{{ $task->judul }}</h3>
                    <span class="text-muted">
                        {{ $task->course->kode }} — {{ $task->course->nama }}
                    </span>
                </div>

                <div>
                    @if ($task->status === 'selesai')
                        <span class="badge bg-success fs-6">Selesai</span>
                    @elseif ($task->status === 'proses')
                        <span class="badge bg-primary fs-6">Sedang dikerjakan</span>
                    @else
                        <span class="badge bg-secondary fs-6">Belum dikerjakan</span>
                    @endif
                </div>
            </div>

            <hr>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small mb-1">Tenggat Waktu</div>
                    <div class="fw-semibold">
                        <i class="bi bi-calendar-event me-2"></i>
                        {{ $task->deadline->format('d F Y, H:i') }}
                    </div>

                    @if ($task->status !== 'selesai' && $task->deadline->isPast())
                        <span class="badge bg-danger mt-2">Tenggat terlewat</span>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="text-muted small mb-1">Dibuat Pada</div>
                    <div class="fw-semibold">
                        <i class="bi bi-clock me-2"></i>
                        {{ $task->created_at->format('d F Y, H:i') }}
                    </div>
                </div>

                <div class="col-12">
                    <div class="text-muted small mb-2">Deskripsi Tugas</div>
                    <div class="border rounded p-3 bg-light" style="white-space: pre-line;">{{ $task->deskripsi ?: 'Tidak ada deskripsi tugas.' }}</div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('mahasiswa.tasks.edit', $task->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit Tugas
                </a>

                <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-outline-secondary">
                    Kembali
                </a>

                <form
                    action="{{ route('mahasiswa.tasks.destroy', $task->id) }}"
                    method="POST"
                    class="form-delete"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Hapus Tugas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
