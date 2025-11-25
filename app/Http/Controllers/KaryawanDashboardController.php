<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        // contoh email: 394021@oei.com -> ambil nip sebelum '@'
        $email = Auth::user()->email;
        $nip = explode('@', $email)[0];

        // Data absensi berdasarkan NIP
        $absensi = DB::select("
            EXEC SPH_Reports_KaryawanAbsensi_1 ?
        ", [$nip]);

        // Detail karyawan
        $karyawan = DB::selectOne("
            EXEC SPH_KaryawanList_1 ?, '', '', '', ''
        ", [$nip]);

        return view('dashboard.karyawan', compact('karyawan','absensi'));
    }
}
