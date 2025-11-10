<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua user untuk ditampilkan di tabel
        $users = User::all();

        return view('users.index', compact('users'));
    }
}
