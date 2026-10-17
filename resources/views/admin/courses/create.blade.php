@extends('layouts.app')
@section('title', 'Tambah Mata Kuliah')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-plus-circle me-1"></i>Form Tambah Mata Kuliah</h5>
            @include('admin.courses._form')
        </div>
    </div>
@endsection
