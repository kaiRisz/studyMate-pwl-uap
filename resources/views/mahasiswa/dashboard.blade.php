@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@section('content')
    <div class="card"><div class="card-body">
        <h5>Selamat datang, {{ auth()->user()->name }}</h5>
        <p class="text-muted mb-0">Dashboard Mahasiswa sedang dikerjakan.</p>
    </div></div>
@endsection