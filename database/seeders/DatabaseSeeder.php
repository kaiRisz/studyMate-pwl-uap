<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin StudyMate',
            'email' => 'admin@studymate.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $mahasiswa = User::create([
            'name' => 'Mahasiswa Contoh',
            'email' => 'mahasiswa@studymate.test',
            'nim' => '2024001',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $pwl = Course::create([
            'kode' => 'IF301', 'nama' => 'Pemrograman Web Lanjut', 'sks' => 3,
            'dosen' => 'Dosen PWL', 'hari' => 'Senin',
            'jam_mulai' => '08:00', 'jam_selesai' => '10:30', 'ruangan' => 'Lab 1',
        ]);

        $basdat = Course::create([
            'kode' => 'IF302', 'nama' => 'Basis Data', 'sks' => 3,
            'dosen' => 'Dosen Basdat', 'hari' => 'Selasa',
            'jam_mulai' => '10:00', 'jam_selesai' => '12:30', 'ruangan' => 'R. 204',
        ]);

        Task::create([
            'user_id' => $mahasiswa->id, 'course_id' => $pwl->id,
            'judul' => 'Mengerjakan UTP PWL', 'deskripsi' => 'Aplikasi StudyMate',
            'deadline' => now()->addDays(2), 'status' => 'proses',
        ]);

        Task::create([
            'user_id' => $mahasiswa->id, 'course_id' => $basdat->id,
            'judul' => 'Laporan ERD', 'deskripsi' => 'Buat ERD sistem perpustakaan',
            'deadline' => now()->subDay(), 'status' => 'belum',
        ]);
    }
}