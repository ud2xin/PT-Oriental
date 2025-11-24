<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Department;


class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $bulan = $request->input('bulan', $bulanSekarang);
        $tahun = $request->input('tahun', $tahunSekarang);
        $search = $request->input('search', '');
        $status = $request->input('status', 'all');
        $departemen = $request->input('departemen', '');

        $user = Auth::user();
        $role = $user->role ?? 'karyawan';

        // Ambil daftar departemen (nama)
        $departemenList = Department::pluck('name')->toArray();
        $namaBulan = Carbon::createFromDate(null, $bulan, 1)->locale('id')->translatedFormat('F');

        // Query dasar (gunakan schema ts.attendance_logs di model)
        $query = Attendance::with('user')
            ->whereMonth('tanggal_scan', $bulan)
            ->whereYear('tanggal_scan', $tahun);

        // Role filter
        if ($role === 'super_admin') {
            if ($departemen) {
                $query->where('departemen', $departemen);
                // jika departemen di attendance tabel adalah string — langsung where.
                // jika kamu menggunakan relation user->department, bisa gunakan whereHas seperti contoh sebelumnya.
            }
        } elseif ($role === 'admin') {
            // admin melihat departemen sendiri — jika user punya department->id atau name
            if ($user->department) {
                $query->where('departemen', $user->department->name);
            }
        } else {
            // karyawan hanya melihat dirinya sendiri
            $query->where('nama', $user->name);
        }

        // FILTER STATUS jika kamu punya kolom status (opsional)
        if ($status !== 'all' && $status) {
            // contoh: status 'Hadir', 'Izin', 'Alfa'
            $query->where('status', $status);
        }

        // SEARCH: cari pada beberapa kolom penting sesuai tabelmu
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('pin', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('departemen', 'like', "%{$search}%");
            });
        }

        // Urut dan paginate
        $perPage = 25; // ubah sesuai kebutuhan
        $attendanceData = $query->orderBy('tanggal_scan', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Data untuk view
        $data = [
            'title' => $role === 'super_admin'
                ? 'Rekap Kehadiran Seluruh Karyawan'
                : ($role === 'admin' ? 'Rekap Absensi Departemen ' . ($user->department->name ?? '') : 'Riwayat Kehadiran Saya'),
            'role' => $role,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'departemen' => $departemen,
            'departemenList' => $departemenList,
            'status' => $status,
            'search' => $search,
            'attendanceData' => $attendanceData,
        ];

        if ($role === 'super_admin') {
            return view('attendance.index', $data);
        } elseif ($role === 'admin') {
            return view('attendance.admin', $data);
        } else {
            return view('attendance.user', $data);
        }
    }

    public function checkin(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'tanggal_scan' => $today,
        ]);

        if ($attendance->jam_masuk) {
            return back()->with('info', 'Kamu sudah melakukan check-in hari ini.');
        }

        $attendance->jam_masuk = Carbon::now()->format('H:i:s');
        $attendance->status = 'Hadir';
        $attendance->save();

        return back()->with('success', 'Check-in berhasil!');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal_scan', $today)
            ->first();

        if (!$attendance || !$attendance->jam_masuk) {
            return back()->with('error', 'Kamu belum check-in hari ini.');
        }

        if ($attendance->jam_keluar) {
            return back()->with('info', 'Kamu sudah melakukan check-out.');
        }

        $attendance->jam_keluar = Carbon::now()->format('H:i:s');
        $attendance->save();

        return back()->with('success', 'Check-out berhasil!');
    }

    public function show($id)
    {
        $attendance = Attendance::with('user.department')->findOrFail($id);

        return view('attendance.show', [
            'title' => 'Detail Kehadiran',
            'attendance' => $attendance,
        ]);
    }

    public function showCheckin()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('tanggal_scan', $today)
            ->first();

        return view('attendance.checkin', [
            'user' => $user,
            'todayAttendance' => $attendance,
        ]);
    }

    public function export()
    {
        return 'Fitur export masih dalam pengembangan.';
    }
}
