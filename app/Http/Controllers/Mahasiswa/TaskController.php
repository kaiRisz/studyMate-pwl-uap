<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas milik mahasiswa yang login.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $deadlineFilter = $request->query('deadline');

        $tasks = auth()->user()->tasks()
            ->with('course')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($status, ['belum', 'proses', 'selesai'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->when($deadlineFilter === 'today', function ($query) {
                $query->whereDate('deadline', today());
            })
            ->when($deadlineFilter === 'week', function ($query) {
                $query->whereBetween('deadline', [
                    now()->startOfDay(),
                    now()->addDays(7)->endOfDay(),
                ]);
            })
            ->when($deadlineFilter === 'overdue', function ($query) {
                $query->where('deadline', '<', now())
                    ->where('status', '!=', 'selesai');
            })
            ->orderBy('deadline')
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.tasks.index', compact(
            'tasks',
            'search',
            'status',
            'deadlineFilter'
        ));
    }

    /**
     * Menampilkan form tambah tugas.
     */
    public function create()
    {
        $courses = Course::query()->orderBy('kode')->get();

        return view('mahasiswa.tasks.create', compact('courses'));
    }

    /**
     * Menyimpan tugas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id'),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
        ]);

        auth()->user()->tasks()->create([
            ...$validated,
            'status' => 'belum',
        ]);

        return redirect()
            ->route('mahasiswa.tasks.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail tugas.
     */
    public function show(string $id)
    {
        $task = auth()->user()->tasks()
            ->with('course')
            ->findOrFail($id);

        return view('mahasiswa.tasks.show', compact('task'));
    }

    /**
     * Menampilkan form edit tugas.
     */
    public function edit(string $id)
    {
        $task = auth()->user()->tasks()->findOrFail($id);
        $courses = Course::query()->orderBy('kode')->get();

        return view('mahasiswa.tasks.edit', compact('task', 'courses'));
    }

    /**
     * Memperbarui tugas.
     */
    public function update(Request $request, string $id)
    {
        $task = auth()->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id'),
            ],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in(['belum', 'proses', 'selesai']),
            ],
        ]);

        $task->update($validated);

        return redirect()
            ->route('mahasiswa.tasks.show', $task->id)
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas.
     */
    public function destroy(string $id)
    {
        $task = auth()->user()->tasks()->findOrFail($id);
        $task->delete();

        return redirect()
            ->route('mahasiswa.tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Mengubah status tugas secara langsung.
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(['belum', 'proses', 'selesai']),
            ],
        ]);

        $task = auth()->user()->tasks()->findOrFail($id);
        $task->update(['status' => $validated['status']]);

        return redirect()
            ->back()
            ->with('success', 'Status tugas berhasil diperbarui.');
    }
}
