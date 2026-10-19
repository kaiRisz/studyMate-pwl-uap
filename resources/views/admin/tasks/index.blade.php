@extends('layouts.app')

@section('title', 'Semua Tugas')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h4 class="mb-1">Semua Tugas</h4>
                    <p class="text-muted mb-0">Daftar tugas dari seluruh mahasiswa.</p>
                </div>
                <form action="{{ route('admin.tasks.index') }}" method="GET" class="d-flex gap-2" style="max-width: 420px; width: 100%;">
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari judul, mahasiswa, atau mata kuliah...">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if($search !== '')
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Judul Tugas</th>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $loop->iteration + (($tasks->currentPage() - 1) * $tasks->perPage()) }}</td>
                                <td>{{ $task->judul }}</td>
                                <td>{{ $task->user?->name ?? '-' }}</td>
                                <td>{{ $task->course?->nama ?? '-' }}</td>
                                <td>{{ $task->deadline?->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'belum' => 'bg-secondary',
                                            'proses' => 'bg-warning text-dark',
                                            'selesai' => 'bg-success',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusClass[$task->status] ?? 'bg-secondary' }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    @if($search !== '')
                                        Tidak ada tugas yang cocok dengan "{{ $search }}".
                                    @else
                                        Belum ada tugas yang dibuat mahasiswa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tasks->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
