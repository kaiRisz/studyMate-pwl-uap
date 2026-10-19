<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $tasks = Task::query()
            ->with(['user', 'course'])
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . strtolower($search) . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->whereRaw('LOWER(judul) LIKE ?', [$keyword])
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->whereRaw('LOWER(name) LIKE ?', [$keyword]);
                        })
                        ->orWhereHas('course', function ($courseQuery) use ($keyword) {
                            $courseQuery->whereRaw('LOWER(nama) LIKE ?', [$keyword]);
                        });
                });
            })
            ->orderByDesc('deadline')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tasks.index', compact('tasks', 'search'));
    }
}
