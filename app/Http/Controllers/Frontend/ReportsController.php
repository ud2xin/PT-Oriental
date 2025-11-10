<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'karyawan';

        // Filter parameters
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $departemen = $request->get('departemen', 'all');

        // Generate nama bulan
        $namaBulan = Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y');

        // Data untuk Super Admin - bisa lihat semua departemen
        if ($role === 'super_admin') {
            $departemenList = [
                'all' => 'Semua Departemen',
                'Produksi' => 'Produksi',
                'QC' => 'Quality Control',
                'Office' => 'Office',
                'IT' => 'IT',
                'HR' => 'Human Resources',
                'Finance' => 'Finance'
            ];

            // Summary statistics
            $summary = [
                'total_karyawan' => $departemen === 'all' ? 248 : 45,
                'total_hari_kerja' => 22,
                'rata_rata_kehadiran' => 93.5,
                'total_hadir' => 5456,
                'total_izin' => 176,
                'total_alfa' => 176,
                'total_terlambat' => 330,
            ];

            // Data tabel rekap per karyawan (sample data)
            $rekapKaryawan = [
                ['nama' => 'Ahmad Fauzi', 'departemen' => 'Produksi', 'hadir' => 20, 'izin' => 1, 'alfa' => 1, 'terlambat' => 3, 'persentase' => 90.9],
                ['nama' => 'Siti Nurhaliza', 'departemen' => 'QC', 'hadir' => 21, 'izin' => 1, 'alfa' => 0, 'terlambat' => 2, 'persentase' => 95.5],
                ['nama' => 'Budi Santoso', 'departemen' => 'Office', 'hadir' => 22, 'izin' => 0, 'alfa' => 0, 'terlambat' => 1, 'persentase' => 100],
                ['nama' => 'Rina Wijaya', 'departemen' => 'IT', 'hadir' => 19, 'izin' => 2, 'alfa' => 1, 'terlambat' => 4, 'persentase' => 86.4],
                ['nama' => 'Joko Widodo', 'departemen' => 'Produksi', 'hadir' => 21, 'izin' => 0, 'alfa' => 1, 'terlambat' => 2, 'persentase' => 95.5],
                ['nama' => 'Dewi Lestari', 'departemen' => 'QC', 'hadir' => 20, 'izin' => 1, 'alfa' => 1, 'terlambat' => 3, 'persentase' => 90.9],
                ['nama' => 'Andi Prasetyo', 'departemen' => 'Produksi', 'hadir' => 22, 'izin' => 0, 'alfa' => 0, 'terlambat' => 0, 'persentase' => 100],
                ['nama' => 'Maya Sari', 'departemen' => 'Office', 'hadir' => 21, 'izin' => 1, 'alfa' => 0, 'terlambat' => 1, 'persentase' => 95.5],
                ['nama' => 'Eko Prasetyo', 'departemen' => 'IT', 'hadir' => 20, 'izin' => 2, 'alfa' => 0, 'terlambat' => 2, 'persentase' => 90.9],
                ['nama' => 'Fitri Handayani', 'departemen' => 'Office', 'hadir' => 19, 'izin' => 2, 'alfa' => 1, 'terlambat' => 5, 'persentase' => 86.4],
            ];

            // Chart data - kehadiran per minggu
            $chartLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
            $chartHadir = [1350, 1380, 1320, 1406];
            $chartIzin = [40, 45, 48, 43];
            $chartAlfa = [42, 40, 45, 49];

            return view('reports.index', compact(
                'role', 'departemenList', 'departemen', 'bulan', 'tahun',
                'namaBulan', 'summary', 'rekapKaryawan', 'chartLabels',
                'chartHadir', 'chartIzin', 'chartAlfa'
            ));
        }

        // Data untuk Admin Departemen - hanya departemennya
        if ($role === 'admin') {
            $userDepartemen = $user->departemen ?? 'Produksi';

            $summary = [
                'total_karyawan' => 45,
                'total_hari_kerja' => 22,
                'rata_rata_kehadiran' => 93.3,
                'total_hadir' => 990,
                'total_izin' => 22,
                'total_alfa' => 22,
                'total_terlambat' => 60,
            ];

            $rekapKaryawan = [
                ['nama' => 'Ahmad Fauzi', 'hadir' => 20, 'izin' => 1, 'alfa' => 1, 'terlambat' => 3, 'persentase' => 90.9],
                ['nama' => 'Joko Widodo', 'hadir' => 21, 'izin' => 0, 'alfa' => 1, 'terlambat' => 2, 'persentase' => 95.5],
                ['nama' => 'Andi Prasetyo', 'hadir' => 22, 'izin' => 0, 'alfa' => 0, 'terlambat' => 0, 'persentase' => 100],
                ['nama' => 'Surya Pratama', 'hadir' => 21, 'izin' => 1, 'alfa' => 0, 'terlambat' => 1, 'persentase' => 95.5],
                ['nama' => 'Dedi Supardi', 'hadir' => 20, 'izin' => 2, 'alfa' => 0, 'terlambat' => 2, 'persentase' => 90.9],
            ];

            $chartLabels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'];
            $chartHadir = [42, 43, 41, 44];
            $chartIzin = [2, 1, 3, 1];
            $chartAlfa = [1, 1, 1, 0];

            return view('reports.admin', compact(
                'role', 'userDepartemen', 'bulan', 'tahun', 'namaBulan',
                'summary', 'rekapKaryawan', 'chartLabels', 'chartHadir',
                'chartIzin', 'chartAlfa'
            ));
        }

        // Data untuk Karyawan - hanya data pribadi
        $rekapPribadi = [
            'bulan' => $namaBulan,
            'hadir' => 20,
            'izin' => 1,
            'alfa' => 1,
            'terlambat' => 3,
            'total_hari_kerja' => 22,
            'persentase_kehadiran' => 90.9,
        ];

        // Detail harian
        $detailHarian = [];
        for ($i = 1; $i <= 22; $i++) {
            $tanggal = Carbon::create($tahun, $bulan, $i);
            $status = 'Hadir';
            $jamMasuk = '08:0' . rand(0, 5);
            $jamKeluar = '17:0' . rand(0, 5);

            if ($i == 5) {
                $status = 'Izin';
                $jamMasuk = '-';
                $jamKeluar = '-';
            } elseif ($i == 15) {
                $status = 'Alfa';
                $jamMasuk = '-';
                $jamKeluar = '-';
            }

            $detailHarian[] = [
                'tanggal' => $tanggal->format('Y-m-d'),
                'hari' => $tanggal->locale('id')->translatedFormat('l'),
                'jam_masuk' => $jamMasuk,
                'jam_keluar' => $jamKeluar,
                'status' => $status,
            ];
        }

        return view('reports.karyawan', compact(
            'role', 'bulan', 'tahun', 'namaBulan', 'rekapPribadi', 'detailHarian'
        ));
    }

    public function export(Request $request)
    {
        // Export to Excel functionality
        // Implementasi export akan dilakukan nanti
        return redirect()->back()->with('success', 'Data berhasil diexport!');
    }
}
