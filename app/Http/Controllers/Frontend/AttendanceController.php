<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $bulanSekarang = Carbon::now()->translatedFormat('F');
        $tahunSekarang = Carbon::now()->year;

        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', $tahunSekarang);

        $user = Auth::user();
        $role = $user->role ?? 'karyawan';

        // Variabel umum untuk filter
        $departemenList = ['Produksi', 'QC', 'Office', 'IT', 'HRD', 'Finance'];
        $departemen = $request->input('departemen', $user->departemen ?? '');
        $status = $request->input('status', 'all');
        $search = $request->input('search', '');
        $namaBulan = Carbon::createFromDate(null, $bulan, 1)->locale('id')->translatedFormat('F');

        // Dummy data absensi (bisa diganti dari database)
        $attendanceData = [
            [
                'id' => 1,
                'nama' => 'Ahmad Fauzi',
                'departemen' => 'Produksi',
                'tanggal' => Carbon::now()->toDateString(),
                'jam_masuk' => '08:02',
                'jam_keluar' => '17:00',
                'status' => 'Hadir',
                'keterangan' => '-'
            ],
            [
                'id' => 2,
                'nama' => 'Siti Nurhaliza',
                'departemen' => 'QC',
                'tanggal' => Carbon::now()->toDateString(),
                'jam_masuk' => '08:12',
                'jam_keluar' => '-',
                'status' => 'Izin',
                'keterangan' => 'Urusan keluarga'
            ],
            [
                'id' => 3,
                'nama' => 'Budi Santoso',
                'departemen' => 'Office',
                'tanggal' => Carbon::now()->toDateString(),
                'jam_masuk' => '-',
                'jam_keluar' => '-',
                'status' => 'Alfa',
                'keterangan' => 'Tanpa keterangan'
            ],
        ];

        // SUPER ADMIN
        if ($role === 'super_admin') {
            $data = [
                'title' => 'Rekap Kehadiran Seluruh Karyawan',
                'role' => $role,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'namaBulan' => $namaBulan,
                'departemen' => 'Semua Departemen',
                'departemenList' => $departemenList,
                'status' => $status,
                'search' => $search,
                'attendanceData' => $attendanceData,
            ];

            return view('attendance.index', $data);
        }

        // ADMIN DEPARTEMEN
        if ($role === 'admin') {
            $data = [
                'title' => 'Rekap Absensi Departemen ' . ($user->departemen ?? 'Produksi'),
                'role' => $role,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'namaBulan' => $namaBulan,
                'departemen' => $user->departemen ?? 'Produksi',
                'departemenList' => $departemenList,
                'status' => $status,
                'search' => $search,
                'attendanceData' => array_filter($attendanceData, fn($d) => $d['departemen'] === ($user->departemen ?? 'Produksi')),
            ];

            return view('attendance.admin', $data);
        }

        // KARYAWAN
        return redirect()->route('attendance.checkin');
    }

    public function checkin(Request $request)
    {
        return back()->with('success', 'Check-in berhasil!');
    }

    public function checkout(Request $request)
    {
        return back()->with('success', 'Check-out berhasil!');
    }

    public function export()
    {
        return 'Fitur export masih dalam pengembangan.';
    }

    public function show($id)
    {
        // contoh data dummy
        $data = [
            'title' => 'Detail Kehadiran',
            'id' => $id,
            'nama' => 'Ahmad Fauzi',
            'departemen' => 'Produksi',
            'tanggal' => now()->format('d F Y'),
            'jam_masuk' => '08:10',
            'jam_keluar' => '17:00',
            'status' => 'Hadir'
        ];

        return view('attendance.show', $data);
    }
}
