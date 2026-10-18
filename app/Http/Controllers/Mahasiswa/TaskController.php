<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:belum,proses,selesai'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'deadline' => ['nullable', 'in:hari_ini,minggu_ini,terlambat'],
        ]);

        $search = trim($filters['q'] ?? '');
        $status = $filters['status'] ?? '';
        $courseId = $filters['course_id'] ?? '';
        $deadlineFilter = $filters['deadline'] ?? '';

        $tasks = Task::query()
            ->where('user_id', $request->user()->id)
            ->with('course')
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%'.mb_strtolower($search).'%';
                $query->whereRaw('LOWER(judul) LIKE ?', [$keyword]);
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($courseId !== '', fn ($query) => $query->where('course_id', $courseId))
            ->when($deadlineFilter === 'hari_ini', fn ($query) => $query->whereDate('deadline', today()))
            ->when($deadlineFilter === 'minggu_ini', fn ($query) => $query->whereBetween('deadline', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]))
            ->when($deadlineFilter === 'terlambat', fn ($query) => $query
                ->where('status', '!=', 'selesai')
                ->where('deadline', '<', now()))
            ->orderByRaw("CASE WHEN status = 'selesai' THEN 1 ELSE 0 END")
            ->orderBy('deadline')
            ->paginate(10)
            ->withQueryString();

        $courses = Course::query()->orderBy('nama')->get(['id', 'nama']);

        return view('mahasiswa.tasks.index', compact(
            'tasks',
            'courses',
            'search',
            'status',
            'courseId',
            'deadlineFilter',
        ));
    }

    public function create()
    {
        $courses = Course::query()->orderBy('nama')->get(['id', 'nama']);

        return view('mahasiswa.tasks.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'in:belum,proses,selesai'],
        ], [
            'required' => ':attribute wajib diisi.',
            'course_id.exists' => 'Mata kuliah yang dipilih tidak tersedia.',
            'deadline.date' => 'Format deadline tidak valid.',
            'status.in' => 'Status tugas tidak valid.',
        ], [
            'judul' => 'judul tugas',
            'deskripsi' => 'deskripsi',
            'course_id' => 'mata kuliah',
            'deadline' => 'deadline',
            'status' => 'status',
        ]);

        $request->user()->tasks()->create($data);

        return redirect()->route('mahasiswa.tasks.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);
        $task->load('course');

        return view('mahasiswa.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);
        $courses = Course::query()->orderBy('nama')->get(['id', 'nama']);

        return view('mahasiswa.tasks.edit', compact('task', 'courses'));
    }

    public function update(Request $request, Task $task)
    {
        $this->ensureTaskBelongsToUser($task);

        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:10000'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'in:belum,proses,selesai'],
        ], [
            'required' => ':attribute wajib diisi.',
            'course_id.exists' => 'Mata kuliah yang dipilih tidak tersedia.',
            'deadline.date' => 'Format deadline tidak valid.',
            'status.in' => 'Status tugas tidak valid.',
        ], [
            'judul' => 'judul tugas',
            'deskripsi' => 'deskripsi',
            'course_id' => 'mata kuliah',
            'deadline' => 'deadline',
            'status' => 'status',
        ]);

        $task->update($data);

        return redirect()->route('mahasiswa.tasks.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->ensureTaskBelongsToUser($task);

        $data = $request->validate([
            'status' => ['required', 'in:belum,proses,selesai'],
        ], [
            'status.in' => 'Status tugas tidak valid.',
        ]);

        $task->update($data);

        return redirect()->route('mahasiswa.tasks.index')
            ->with('success', 'Status tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $this->ensureTaskBelongsToUser($task);
        $task->delete();

        return redirect()->route('mahasiswa.tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    private function ensureTaskBelongsToUser(Task $task): void
    {
        abort_unless((int) $task->user_id === (int) auth()->id(), 404);
    }
}
