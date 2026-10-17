<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Daftar mata kuliah & jadwal (read-only) + pencarian + pagination.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $courses = Course::query()
            ->when($search !== '', function ($query) use ($search) {
                // LOWER() di kedua sisi supaya pencarian tidak peduli huruf besar/kecil
                $keyword = '%'.mb_strtolower($search).'%';

                $query->where(function ($q) use ($keyword) {
                    $q->whereRaw('LOWER(kode) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(nama) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(dosen) LIKE ?', [$keyword]);
                });
            })
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.courses.index', compact('courses', 'search'));
    }

    public function show(Course $course)
    {
        // Jumlah tugas milik mahasiswa yang sedang login pada mata kuliah ini
        $jumlahTugasSaya = $course->tasks()->where('user_id', auth()->id())->count();

        return view('mahasiswa.courses.show', compact('course', 'jumlahTugasSaya'));
    }
}
