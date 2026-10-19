@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Tugas Saya</h4>
                    <p class="text-muted mb-0">Kelola tugas dan pantau deadline kuliahmu.</p>
                </div>
                <a href="{{ route('mahasiswa.tasks.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
                </a>
            </div>

            <form action="{{ route('mahasiswa.tasks.index') }}" method="GET" class="row g-2 align-items-end mb-4">
                <div class="col-12 col-md-6 col-xl-3">
                    <label for="task-search" class="form-label small fw-semibold">Cari judul</label>
                    <input id="task-search" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Contoh: laporan">
                </div>
                <div class="col-12 col-sm-6 col-xl-2">
                    <label for="task-status" class="form-label small fw-semibold">Status</label>
                    <select id="task-status" name="status" class="form-select">
                        <option value="">Semua status</option>
                        <option value="belum" @selected($status === 'belum')>Belum dikerjakan</option>
                        <option value="proses" @selected($status === 'proses')>Dikerjakan</option>
                        <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <label for="task-course" class="form-label small fw-semibold">Mata kuliah</label>
                    <select id="task-course" name="course_id" class="form-select">
                        <option value="">Semua mata kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected((string) $courseId === (string) $course->id)>{{ $course->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xl-2">
                    <label for="task-deadline" class="form-label small fw-semibold">Deadline</label>
                    <select id="task-deadline" name="deadline" class="form-select">
                        <option value="">Semua deadline</option>
                        <option value="hari_ini" @selected($deadlineFilter === 'hari_ini')>Hari ini</option>
                        <option value="minggu_ini" @selected($deadlineFilter === 'minggu_ini')>Minggu ini</option>
                        <option value="terlambat" @selected($deadlineFilter === 'terlambat')>Terlambat</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xl-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
                    @if($search !== '' || $status !== '' || $courseId !== '' || $deadlineFilter !== '')
                        <a href="{{ route('mahasiswa.tasks.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Judul Tugas</th>
                            <th>Mata Kuliah</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            @php
                                $isOverdue = $task->status !== 'selesai' && $task->deadline->isPast();
                                $isDueSoon = !$isOverdue && $task->status !== 'selesai' && $task->deadline->lte(now()->addDays(3));
                                $statusClasses = ['belum' => 'bg-secondary', 'proses' => 'bg-warning text-dark', 'selesai' => 'bg-success'];
                                $statusLabels = ['belum' => 'Belum dikerjakan', 'proses' => 'Dikerjakan', 'selesai' => 'Selesai'];
                            @endphp
                            <tr>
                                <td>{{ $tasks->firstItem() + $loop->index }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.tasks.show', $task) }}" class="fw-semibold text-decoration-none">{{ $task->judul }}</a>
                                    @if($isOverdue)
                                        <span class="badge bg-danger ms-1">Terlambat</span>
                                    @endif
                                </td>
                                <td>{{ $task->course?->nama ?? '-' }}</td>
                                <td class="text-nowrap {{ $isOverdue ? 'text-danger fw-semibold' : ($isDueSoon ? 'text-warning-emphasis fw-semibold' : '') }}">
                                    {{ $task->deadline->format('d M Y, H:i') }}
                                </td>
                                <td><span class="badge {{ $statusClasses[$task->status] ?? 'bg-secondary' }}">{{ $statusLabels[$task->status] ?? ucfirst($task->status) }}</span></td>
                                <td class="text-end text-nowrap">
                                    @if($task->status !== 'selesai')
                                        <form action="{{ route('mahasiswa.tasks.status', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $task->status === 'belum' ? 'proses' : 'selesai' }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="{{ $task->status === 'belum' ? 'Mulai kerjakan' : 'Tandai selesai' }}">
                                                <i class="bi {{ $task->status === 'belum' ? 'bi-play' : 'bi-check2' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('mahasiswa.tasks.edit', $task) }}" class="btn btn-sm btn-outline-warning" title="Ubah"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('mahasiswa.tasks.destroy', $task) }}" method="POST" class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    @if($search !== '' || $status !== '' || $courseId !== '' || $deadlineFilter !== '')
                                        Tidak ada tugas yang cocok dengan pencarian atau filter.
                                    @else
                                        Belum ada tugas. Tambahkan tugas pertamamu untuk mulai mengatur deadline.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tasks->total() > 0)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                    <small class="text-muted">Menampilkan {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }} dari {{ $tasks->total() }} tugas</small>
                    {{ $tasks->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
