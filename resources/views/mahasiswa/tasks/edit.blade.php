@extends('layouts.app')

@section('title', 'Ubah Tugas')

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <div class="mb-4">
                <h4 class="fw-bold mb-1">Ubah Tugas</h4>
                <p class="text-muted mb-0">Perbarui informasi dan status tugasmu.</p>
            </div>
            @include('mahasiswa.tasks._form')
        </div>
    </div>
@endsection
