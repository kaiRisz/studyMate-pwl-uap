@extends('layouts.app')
@section('title', 'Ubah Mata Kuliah')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-pencil-square me-1"></i>Form Ubah Mata Kuliah</h5>
            @include('admin.courses._form')
        </div>
    </div>
@endsection
