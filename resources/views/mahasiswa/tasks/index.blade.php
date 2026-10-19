@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tugas Saya</h2>
            <p class="text-muted mb-0">Kelola tugas dan pantau tenggat waktumu.</p>
        </div>

        <a href="{{ route('mahasiswa.tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('mahasiswa.tasks.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="q" class="form-label">Cari tugas</label>
                        <input
                            type="text"
                            name="q"
                            id="q"
                            class="form-control"
                            placeholder="Masukkan judul atau deskripsi..."
                            value="{{ $search }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua status</option>
                            <option value="belum" @selected($status === 'belum')>Belum dikerjakan</option>
                            <option value="proses" @selected($status === 'proses')>Sedang dikerjakan</option>
                            <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="deadline" class="form-label">Tenggat waktu</label>
                        <select name="deadline" id="deadline" class="form-select">
                            <option value="">Semua tenggat</option>
                            <option value="today" @selected($deadlineFilter === 'today')>Hari ini</option>
                            <option value="week" @selected($deadlineFilter === 'week')>7 hari ke depan</option>
                            <option value="overdue" @selected($deadlineFilter === 'overdue')>Terlambat</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-outline-primary" aria-label="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('mahasiswa.tasks.index') }}" class="text-decoration-none small">
                        Reset filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($tasks->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-check display-4 text-muted"></i>
                    <h5 class="mt-3">Belum ada tugas</h5>
                    <p class="text-muted mb-3">
                        Tambahkan tugas pertamamu agar lebih mudah mengatur kegiatan kuliah.
                    </p>
                    <a href="{{ route('mahasiswa.tasks.create') }}" class="btn btn-primary">
                        Tambah Tugas
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Mata Kuliah</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $task->judul }}</div>
                                        <div class="small text-muted">
                                            {{ \Illuminate\Support\Str::limit($task->deskripsi ?? '', 65) }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $task->course->kode }} — {{ $task->course->nama }}
                                    </td>
                                    <td>
                                        {{ $task->deadline->format('d/m/Y H:i') }}
                                        @if ($task->status !== 'selesai' && $task->deadline->isPast())
                                            <div class="small text-danger">Terlambat</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($task->status === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif ($task->status === 'proses')
                                            <span class="badge bg-primary">Sedang dikerjakan</span>
                                        @else
                                            <span class="badge bg-secondary">Belum dikerjakan</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a
                                            href="{{ route('mahasiswa.tasks.show', $task->id) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="Detail"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a
                                            href="{{ route('mahasiswa.tasks.edit', $task->id) }}"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form
                                            action="{{ route('mahasiswa.tasks.destroy', $task->id) }}"
                                            method="POST"
                                            class="d-inline form-delete"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
