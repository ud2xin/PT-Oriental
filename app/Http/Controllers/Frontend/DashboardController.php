<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Department;


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

            $today = now()->format('Y-m-d');

            // Total karyawan unik berdasarkan PIN
            $totalKaryawan = Attendance::distinct('pin')->count('pin');

            // Hadir hari ini (io = 'in')
            $hadirHariIni = Attendance::where('tanggal', $today)
                ->where('io', 'in')
                ->distinct('pin')
                ->count('pin');

            // Izin hari ini (sementara workcode = 1)
            $izinHariIni = Attendance::where('tanggal', $today)
                ->where('workcode', 1)
                ->distinct('pin')
                ->count('pin');

            // Alfa
            $alfaHariIni = $totalKaryawan - $hadirHariIni - $izinHariIni;

            // Persentase hadir
            $persentaseKehadiran = $totalKaryawan > 0
                ? round(($hadirHariIni / $totalKaryawan) * 100, 1)
                : 0;

            // Tambahan baru
            $totalDepartemen = Department::count();

            // Dummy
            $hariKerjaBulanIni = 0;

            // Dummy
            $terlatHariIni = 0;

            return view('dashboard.super-admin', compact(
                'totalKaryawan',
                'hadirHariIni',
                'izinHariIni',
                'alfaHariIni',
                'persentaseKehadiran',
                'totalDepartemen',
                'hariKerjaBulanIni',
                'terlatHariIni'
            ));
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
