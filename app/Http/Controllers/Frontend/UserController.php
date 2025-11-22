<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query search dari request
        $search = $request->input('search');

        // Banyak item per halaman, ubah sesuai kebutuhan
        $perPage = 20;

        // Query: eager load department, optional filter by search (name / email / pin)
        $usersQuery = User::with('department')
            ->when($search, function ($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('pin', 'like', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc');

        // paginate
        $users = $usersQuery->paginate($perPage)->withQueryString();

        // kirim ke view
        return view('users.index', compact('users', 'search'));
    }
}
