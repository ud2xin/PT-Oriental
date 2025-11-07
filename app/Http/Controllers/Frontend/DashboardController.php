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
        $role = $user->role ?? 'karyawan';

        // Data statistik untuk Super Admin / HR
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

                // Data untuk grafik mingguan
                'chartLabels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'chartHadir' => [235, 240, 228, 238, 242, 210, 120],

                // 10 Karyawan terlambat
                'karyawanTerlambat' => [
                    ['nama' => 'Ahmad Fauzi', 'departemen' => 'Produksi', 'jam_masuk' => '08:15', 'keterlambatan' => '15 menit'],
                    ['nama' => 'Siti Nurhaliza', 'departemen' => 'QC', 'jam_masuk' => '08:22', 'keterlambatan' => '22 menit'],
                    ['nama' => 'Budi Santoso', 'departemen' => 'Office', 'jam_masuk' => '08:10', 'keterlambatan' => '10 menit'],
                    ['nama' => 'Rina Wijaya', 'departemen' => 'IT', 'jam_masuk' => '08:18', 'keterlambatan' => '18 menit'],
                    ['nama' => 'Joko Widodo', 'departemen' => 'Produksi', 'jam_masuk' => '08:05', 'keterlambatan' => '5 menit'],
                    ['nama' => 'Dewi Lestari', 'departemen' => 'QC', 'jam_masuk' => '08:12', 'keterlambatan' => '12 menit'],
                    ['nama' => 'Andi Prasetyo', 'departemen' => 'Produksi', 'jam_masuk' => '08:08', 'keterlambatan' => '8 menit'],
                    ['nama' => 'Maya Sari', 'departemen' => 'Office', 'jam_masuk' => '08:20', 'keterlambatan' => '20 menit'],
                    ['nama' => 'Eko Prasetyo', 'departemen' => 'IT', 'jam_masuk' => '08:07', 'keterlambatan' => '7 menit'],
                    ['nama' => 'Fitri Handayani', 'departemen' => 'Office', 'jam_masuk' => '08:25', 'keterlambatan' => '25 menit'],
                ],
            ];

            return view('dashboard.super-admin', $data);
        }

        // Data statistik untuk Admin Departemen
        if ($role === 'admin') {
            $departemen = $user->departemen ?? 'Produksi'; // Ambil dari relasi user

            $data = [
                'departemen' => $departemen,
                'totalAnggota' => 45,
                'hadirHariIni' => 42,
                'alfaHariIni' => 2,
                'izinHariIni' => 1,
                'persentaseKehadiran' => 93.3,

                // Data untuk grafik mingguan
                'chartLabels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'chartHadir' => [42, 43, 41, 44, 45, 38, 20],

                // Riwayat kehadiran departemen hari ini
                'kehadiranHariIni' => [
                    ['nama' => 'Ahmad Fauzi', 'jam_masuk' => '08:15', 'status' => 'Hadir'],
                    ['nama' => 'Budi Santoso', 'jam_masuk' => '07:58', 'status' => 'Hadir'],
                    ['nama' => 'Citra Dewi', 'jam_masuk' => '08:02', 'status' => 'Hadir'],
                    ['nama' => 'Doni Saputra', 'jam_masuk' => '08:10', 'status' => 'Hadir'],
                    ['nama' => 'Eka Putri', 'jam_masuk' => '-', 'status' => 'Izin'],
                ],
            ];

            return view('dashboard.admin', $data);
        }

        // Data untuk Karyawan
        $data = [
            'nama' => $user->name,
            'statusHariIni' => 'Sudah Check In',
            'jamCheckIn' => '08:02',
            'jamCheckOut' => null,

            // Progress kehadiran bulan ini
            'hadirBulanIni' => 18,
            'izinBulanIni' => 2,
            'alfaBulanIni' => 1,
            'totalHariKerja' => 21,

            // Riwayat 5 hari terakhir
            'riwayatTerakhir' => [
                ['tanggal' => '2025-11-06', 'jam_masuk' => '08:02', 'jam_keluar' => '17:05', 'status' => 'Hadir'],
                ['tanggal' => '2025-11-05', 'jam_masuk' => '08:10', 'jam_keluar' => '17:02', 'status' => 'Hadir'],
                ['tanggal' => '2025-11-04', 'jam_masuk' => '07:58', 'jam_keluar' => '17:10', 'status' => 'Hadir'],
                ['tanggal' => '2025-11-03', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Izin'],
                ['tanggal' => '2025-11-02', 'jam_masuk' => '08:05', 'jam_keluar' => '17:00', 'status' => 'Hadir'],
            ],
        ];

        return view('dashboard.karyawan', $data);
    }
}
