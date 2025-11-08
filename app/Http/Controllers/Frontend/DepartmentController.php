<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // Data dummy sementara
        $departments = [
            ['id' => 1, 'nama' => 'Produksi', 'jumlah_karyawan' => 120],
            ['id' => 2, 'nama' => 'Quality Control', 'jumlah_karyawan' => 45],
            ['id' => 3, 'nama' => 'IT Support', 'jumlah_karyawan' => 12],
            ['id' => 4, 'nama' => 'Office Administration', 'jumlah_karyawan' => 20],
            ['id' => 5, 'nama' => 'HR & GA', 'jumlah_karyawan' => 18],
        ];

        return view('departments.index', compact('departments'));
    }
}
