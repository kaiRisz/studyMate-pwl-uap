@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h4 class="mb-1">Daftar Mahasiswa</h4>
                    <p class="text-muted mb-0">Data mahasiswa yang sudah mendaftar.</p>
                </div>
                <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2" style="max-width: 420px; width: 100%;">
                    <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Cari nama, email, atau NIM...">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if($search !== '')
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIM</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->nim ?? '-' }}</td>
                                <td><span class="badge bg-info-subtle text-info-emphasis">{{ ucfirst($student->role) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    @if($search !== '')
                                        Tidak ada mahasiswa yang cocok dengan "{{ $search }}".
                                    @else
                                        Belum ada mahasiswa yang terdaftar.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
