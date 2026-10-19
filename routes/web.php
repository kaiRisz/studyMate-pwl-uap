<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Mahasiswa\CourseController as MahasiswaCourseController;
use App\Http\Controllers\Mahasiswa\TaskController;
use Illuminate\Support\Facades\Route;

// Halaman utama: arahkan sesuai status login dan role
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(
        auth()->user()->role === 'admin' ? 'admin.dashboard' : 'mahasiswa.dashboard'
    );
});

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ===== ADMIN =====
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Rizka: CRUD Mata Kuliah
        Route::resource('courses', AdminCourseController::class);

        // Fahmi: daftar mahasiswa & semua tugas (read-only)
        Route::get('/mahasiswa', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/tugas', [AdminTaskController::class, 'index'])->name('tasks.index');
    });

    // ===== MAHASISWA =====
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswa'])->name('dashboard');

        // Rizka: daftar mata kuliah (read-only)
        Route::resource('courses', MahasiswaCourseController::class)->only(['index', 'show']);

        // Velix: CRUD Tugas (tambah route ubah status di sini)
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
        Route::resource('tasks', TaskController::class);
    });
});
