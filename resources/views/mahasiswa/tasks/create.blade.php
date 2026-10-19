@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Tambah Tugas</h4>
                <p class="text-muted mb-0">Catat tugas baru beserta mata kuliah dan deadline-nya.</p>
            </div>
            @include('mahasiswa.tasks._form')
        </div>
    </div>
@endsection
