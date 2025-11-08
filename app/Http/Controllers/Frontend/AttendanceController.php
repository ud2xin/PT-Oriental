<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role ?? 'karyawan';

        // Super Admin - lihat semua data absensi
        if ($role === 'super_admin') {
            $data = [
                'title' => 'Rekap Kehadiran Seluruh Karyawan',
                'totalKaryawan' => 248,
                'hadirHariIni' => 232,
                'izinHariIni' => 8,
                'alfaHariIni' => 8,
                'rataRataKehadiran' => 93.5,
                'riwayatAbsensi' => [
                    ['nama' => 'Ahmad Fauzi', 'departemen' => 'Produksi', 'jam_masuk' => '08:02', 'jam_keluar' => '17:00', 'status' => 'Hadir'],
                    ['nama' => 'Siti Nurhaliza', 'departemen' => 'QC', 'jam_masuk' => '08:12', 'jam_keluar' => '-', 'status' => 'Izin'],
                    ['nama' => 'Budi Santoso', 'departemen' => 'Office', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Alfa'],
                ],
            ];

            return view('attendance.index', $data);
        }

        // Admin Departemen - hanya lihat absensi departemennya sendiri
        if ($role === 'admin') {
            $data = [
                'title' => 'Rekap Absensi Departemen ' . ($user->departemen ?? 'Produksi'),
                'departemen' => $user->departemen ?? 'Produksi',
                'totalAnggota' => 45,
                'hadirHariIni' => 42,
                'izinHariIni' => 1,
                'alfaHariIni' => 2,
                'riwayatDepartemen' => [
                    ['nama' => 'Ahmad Fauzi', 'jam_masuk' => '08:15', 'jam_keluar' => '17:02', 'status' => 'Hadir'],
                    ['nama' => 'Budi Santoso', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Alfa'],
                    ['nama' => 'Citra Dewi', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Izin'],
                ],
            ];

            return view('attendance.admin', $data);
        }

        // Karyawan - arahkan ke halaman checkin/checkout
        return redirect()->route('attendance.checkin');
    }
}
