<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard');
    }

    public function mahasiswa()
    {
        return view('mahasiswa.dashboard');
    }
}