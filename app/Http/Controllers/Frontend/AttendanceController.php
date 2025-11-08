<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance history/data
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? 'karyawan';

        // Filter parameters
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $departemen = $request->get('departemen', 'all');

        $namaBulan = Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y');

        // For Super Admin - can see all employees and departments
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

            // Sample data - replace with actual database query
            $attendanceData = [
                ['id' => 1, 'nama' => 'Ahmad Fauzi', 'departemen' => 'Produksi', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:15', 'jam_keluar' => '17:05', 'status' => 'Hadir', 'keterangan' => 'Terlambat 15 menit'],
                ['id' => 2, 'nama' => 'Siti Nurhaliza', 'departemen' => 'QC', 'tanggal' => '2025-11-09', 'jam_masuk' => '07:58', 'jam_keluar' => '17:02', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 3, 'nama' => 'Budi Santoso', 'departemen' => 'Office', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:02', 'jam_keluar' => '17:10', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 4, 'nama' => 'Rina Wijaya', 'departemen' => 'IT', 'tanggal' => '2025-11-09', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Izin', 'keterangan' => 'Sakit'],
                ['id' => 5, 'nama' => 'Joko Widodo', 'departemen' => 'Produksi', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:05', 'jam_keluar' => '17:00', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 6, 'nama' => 'Dewi Lestari', 'departemen' => 'QC', 'tanggal' => '2025-11-09', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Alfa', 'keterangan' => 'Tidak ada kabar'],
                ['id' => 7, 'nama' => 'Andi Prasetyo', 'departemen' => 'Produksi', 'tanggal' => '2025-11-09', 'jam_masuk' => '07:55', 'jam_keluar' => '17:05', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 8, 'nama' => 'Maya Sari', 'departemen' => 'Office', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:20', 'jam_keluar' => '17:15', 'status' => 'Hadir', 'keterangan' => 'Terlambat 20 menit'],
            ];

            return view('attendance.index', compact(
                'role', 'attendanceData', 'bulan', 'tahun', 'namaBulan',
                'status', 'search', 'departemen', 'departemenList'
            ));
        }

        // For Admin Departemen - only see their department
        if ($role === 'admin') {
            $userDepartemen = $user->departemen ?? 'Produksi';

            // Sample data - filtered by department
            $attendanceData = [
                ['id' => 1, 'nama' => 'Ahmad Fauzi', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:15', 'jam_keluar' => '17:05', 'status' => 'Hadir', 'keterangan' => 'Terlambat 15 menit'],
                ['id' => 5, 'nama' => 'Joko Widodo', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:05', 'jam_keluar' => '17:00', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 7, 'nama' => 'Andi Prasetyo', 'tanggal' => '2025-11-09', 'jam_masuk' => '07:55', 'jam_keluar' => '17:05', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 9, 'nama' => 'Surya Pratama', 'tanggal' => '2025-11-09', 'jam_masuk' => '08:02', 'jam_keluar' => '17:02', 'status' => 'Hadir', 'keterangan' => '-'],
                ['id' => 10, 'nama' => 'Dedi Supardi', 'tanggal' => '2025-11-09', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Izin', 'keterangan' => 'Keperluan keluarga'],
            ];

            return view('attendance.admin', compact(
                'role', 'userDepartemen', 'attendanceData', 'bulan', 'tahun',
                'namaBulan', 'status', 'search'
            ));
        }

        // For Karyawan - only personal data
        $attendanceData = [
            ['tanggal' => '2025-11-09', 'hari' => 'Sabtu', 'jam_masuk' => '08:02', 'jam_keluar' => '17:05', 'status' => 'Hadir', 'keterangan' => '-'],
            ['tanggal' => '2025-11-08', 'hari' => 'Jumat', 'jam_masuk' => '08:10', 'jam_keluar' => '17:02', 'status' => 'Hadir', 'keterangan' => 'Terlambat 10 menit'],
            ['tanggal' => '2025-11-07', 'hari' => 'Kamis', 'jam_masuk' => '07:58', 'jam_keluar' => '17:10', 'status' => 'Hadir', 'keterangan' => '-'],
            ['tanggal' => '2025-11-06', 'hari' => 'Rabu', 'jam_masuk' => '-', 'jam_keluar' => '-', 'status' => 'Izin', 'keterangan' => 'Keperluan keluarga'],
            ['tanggal' => '2025-11-05', 'hari' => 'Selasa', 'jam_masuk' => '08:05', 'jam_keluar' => '17:00', 'status' => 'Hadir', 'keterangan' => '-'],
            ['tanggal' => '2025-11-04', 'hari' => 'Senin', 'jam_masuk' => '08:15', 'jam_keluar' => '17:08', 'status' => 'Hadir', 'keterangan' => 'Terlambat 15 menit'],
        ];

        return view('attendance.karyawan', compact(
            'role', 'attendanceData', 'bulan', 'tahun', 'namaBulan', 'status'
        ));
    }

    /**
     * Show check-in page
     */
    public function showCheckin()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Check if already checked in today
        // Replace with actual database query
        $todayAttendance = [
            'checked_in' => false,
            'check_in_time' => null,
            'checked_out' => false,
            'check_out_time' => null,
        ];

        // Sample: if user already checked in
        // $todayAttendance['checked_in'] = true;
        // $todayAttendance['check_in_time'] = '08:02';

        return view('attendance.checkin', compact('todayAttendance'));
    }

    /**
     * Process check-in
     */
    public function checkin(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // TODO: Save to database
        // Attendance::create([
        //     'user_id' => $user->id,
        //     'date' => $now->format('Y-m-d'),
        //     'check_in' => $now->format('H:i:s'),
        //     'status' => 'hadir',
        // ]);

        return redirect()->route('attendance.checkin')
            ->with('success', 'Check-in berhasil pada ' . $now->format('H:i'));
    }

    /**
     * Process check-out
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // TODO: Update database
        // Attendance::where('user_id', $user->id)
        //     ->whereDate('date', Carbon::today())
        //     ->update(['check_out' => $now->format('H:i:s')]);

        return redirect()->route('attendance.checkin')
            ->with('success', 'Check-out berhasil pada ' . $now->format('H:i'));
    }

    /**
     * Show attendance detail
     */
    public function show($id)
    {
        // TODO: Show detail attendance record
        return view('attendance.show');
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        // TODO: Export to Excel
        return redirect()->back()->with('success', 'Data berhasil diexport!');
    }
}
