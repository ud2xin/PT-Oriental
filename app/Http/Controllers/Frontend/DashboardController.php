<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika belum login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = $user->role ?? 'karyawan';

        // Super Admin
        if ($role === 'super_admin') {
            $data = [
                'totalKaryawan' => 248,
                'totalDepartemen' => 6,
                'hariKerjaBulanIni' => 22,
                'hadirHariIni' => 232,
                'izinHariIni' => 8,
                'alfaHariIni' => 8,
                'terlatHariIni' => 15,
                'persentaseKehadiran' => 93.5,
                'chartLabels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'chartHadir' => [235, 240, 228, 238, 242, 210, 120],
                'karyawanTerlambat' => [
                    ['nama' => 'Ahmad Fauzi', 'departemen' => 'Produksi', 'jam_masuk' => '08:15', 'keterlambatan' => '15 menit'],
                    ['nama' => 'Siti Nurhaliza', 'departemen' => 'QC', 'jam_masuk' => '08:22', 'keterlambatan' => '22 menit'],
                    // dst...
                ],
            ];

            return view('dashboard.super-admin', $data);
        }

        // Admin Departemen
        if ($role === 'admin') {
            $departemen = $user->departemen ?? 'Produksi';
            $data = [
                'departemen' => $departemen,
                'totalAnggota' => 45,
                'hadirHariIni' => 42,
                'alfaHariIni' => 2,
                'izinHariIni' => 1,
                'persentaseKehadiran' => 93.3,
                'chartLabels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'chartHadir' => [42, 43, 41, 44, 45, 38, 20],
                'kehadiranHariIni' => [
                    ['nama' => 'Ahmad Fauzi', 'jam_masuk' => '08:15', 'status' => 'Hadir'],
                    // dst...
                ],
            ];

            return view('dashboard.admin', $data);
        }

        // Karyawan
        $data = [
            'nama' => $user->name,
            'statusHariIni' => 'Sudah Check In',
            'jamCheckIn' => '08:02',
            'jamCheckOut' => null,
            'hadirBulanIni' => 18,
            'izinBulanIni' => 2,
            'alfaBulanIni' => 1,
            'totalHariKerja' => 21,
            'riwayatTerakhir' => [
                ['tanggal' => '2025-11-06', 'jam_masuk' => '08:02', 'jam_keluar' => '17:05', 'status' => 'Hadir'],
                // dst...
            ],
        ];

        return view('dashboard.karyawan', $data);
    }
}
