<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'courses' => Course::count(),
            'students' => User::where('role', 'mahasiswa')->count(),
            'tasks' => Task::count(),
            'overdueTasks' => Task::where('status', '!=', 'selesai')
                ->where('deadline', '<', now())
                ->count(),
        ];

        $latestTasks = Task::with(['user', 'course'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestTasks'));
    }

    public function mahasiswa()
    {
        $user = auth()->user();
        $tasks = Task::where('user_id', $user->id);

        $stats = [
            'total' => (clone $tasks)->count(),
            'belum' => (clone $tasks)->where('status', 'belum')->count(),
            'proses' => (clone $tasks)->where('status', 'proses')->count(),
            'selesai' => (clone $tasks)->where('status', 'selesai')->count(),
            'terlambat' => (clone $tasks)
                ->where('status', '!=', 'selesai')
                ->where('deadline', '<', now())
                ->count(),
        ];

        $upcomingTasks = (clone $tasks)
            ->with('course')
            ->where('status', '!=', 'selesai')
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact('stats', 'upcomingTasks'));
    }
}