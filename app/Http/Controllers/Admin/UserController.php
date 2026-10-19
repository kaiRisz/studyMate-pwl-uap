<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $students = User::query()
            ->where('role', 'mahasiswa')
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . strtolower($search) . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->whereRaw('LOWER(name) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$keyword])
                        ->orWhereRaw('LOWER(nim) LIKE ?', [$keyword]);
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('students', 'search'));
    }
}
