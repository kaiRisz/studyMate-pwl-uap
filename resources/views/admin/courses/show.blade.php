@extends('layouts.app')
@section('title', 'Detail Mata Kuliah')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-secondary mb-1">{{ $course->kode }}</span>
                    <h4 class="mb-0">{{ $course->nama }}</h4>
                </div>
                <span class="badge bg-primary fs-6">{{ $course->sks }} SKS</span>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-3">Dosen Pengampu</dt>
                <dd class="col-sm-9">{{ $course->dosen }}</dd>

                <dt class="col-sm-3">Hari</dt>
                <dd class="col-sm-9">{{ $course->hari }}</dd>

                <dt class="col-sm-3">Jam</dt>
                <dd class="col-sm-9">{{ substr($course->jam_mulai, 0, 5) }} - {{ substr($course->jam_selesai, 0, 5) }}</dd>

                <dt class="col-sm-3">Ruangan</dt>
                <dd class="col-sm-9">{{ $course->ruangan }}</dd>

                <dt class="col-sm-3">Jumlah Tugas</dt>
                <dd class="col-sm-9">
                    {{ $course->tasks_count }} tugas
                    @if($course->tasks_count > 0)
                        <small class="text-muted">(mata kuliah ini tidak bisa dihapus selama masih ada tugas)</small>
                    @endif
                </dd>
            </dl>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Ubah</a>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
