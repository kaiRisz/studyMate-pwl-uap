@extends('layouts.app')
@section('title', 'Mata Kuliah')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('mahasiswa.courses.index') }}" method="GET" class="d-flex gap-2 mb-3" style="max-width:480px">
                <input type="text" name="q" value="{{ $search }}" class="form-control"
                       placeholder="Cari kode, nama, atau dosen...">
                <button class="btn btn-primary" title="Cari"><i class="bi bi-search"></i></button>
                @if($search !== '')
                    <a href="{{ route('mahasiswa.courses.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Kode</th>
                            <th>Nama Mata Kuliah</th>
                            <th class="text-center">SKS</th>
                            <th>Dosen</th>
                            <th>Jadwal</th>
                            <th>Ruangan</th>
                            <th class="text-center" style="width:80px">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $courses->firstItem() + $loop->index }}</td>
                                <td><span class="badge bg-secondary">{{ $course->kode }}</span></td>
                                <td class="fw-semibold">{{ $course->nama }}</td>
                                <td class="text-center">{{ $course->sks }}</td>
                                <td>{{ $course->dosen }}</td>
                                <td>
                                    {{ $course->hari }}<br>
                                    <small class="text-muted">{{ substr($course->jam_mulai, 0, 5) }} - {{ substr($course->jam_selesai, 0, 5) }}</small>
                                </td>
                                <td>{{ $course->ruangan }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mahasiswa.courses.show', $course) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    @if($search !== '')
                                        Tidak ada mata kuliah yang cocok dengan "{{ $search }}".
                                    @else
                                        Belum ada mata kuliah yang diinput admin.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->total() > 0)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $courses->firstItem() }} - {{ $courses->lastItem() }} dari {{ $courses->total() }} data
                    </small>
                    {{ $courses->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
