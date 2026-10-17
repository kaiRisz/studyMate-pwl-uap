<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Throwable;

class CourseController extends Controller
{
    /**
     * Daftar mata kuliah + pencarian (kode, nama, dosen) + pagination.
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

        return view('admin.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        return view('admin.courses.create', ['hariList' => CourseRequest::HARI]);
    }

    public function store(CourseRequest $request)
    {
        try {
            Course::create($request->validated());
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Mata kuliah gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(Course $course)
    {
        $course->loadCount('tasks');

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', [
            'course'   => $course,
            'hariList' => CourseRequest::HARI,
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            $course->update($request->validated());
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Mata kuliah gagal diperbarui. Silakan coba lagi.');
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    /**
     * Hapus mata kuliah. Ditolak dengan pesan ramah jika masih punya tugas.
     */
    public function destroy(Course $course)
    {
        if ($course->tasks()->exists()) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Mata kuliah "'.$course->nama.'" tidak bisa dihapus karena masih memiliki tugas.');
        }

        try {
            $course->delete();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('admin.courses.index')
                ->with('error', 'Mata kuliah gagal dihapus. Pastikan data tidak sedang dipakai.');
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
