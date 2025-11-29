<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Department;
use Illuminate\Support\Facades\DB;


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

            // 1. Ambil karyawan aktif dari SP
            $karyawanAktif = collect(DB::select("EXEC SPH_KaryawanList_1 '', '', '', '', ''"))
                ->where('JJIK_PART', 'ACTIVE');

            // 2. Hitung total karyawan aktif
            $totalKaryawan = $karyawanAktif->count();

            // 3. Ambil daftar PIN karyawan aktif
            $pinAktif = $karyawanAktif->pluck('empl_nmbr');

            // 4. Hadir / Izin / Alfa
            $hadirHariIni = Attendance::where('tanggal', $today)
                ->whereIn('pin', $pinAktif)
                ->where('io', 'in')
                ->distinct('pin')
                ->count('pin');

            $izinHariIni = Attendance::where('tanggal', $today)
                ->whereIn('pin', $pinAktif)
                ->where('workcode', 1)
                ->distinct('pin')
                ->count('pin');

            $alfaHariIni = $totalKaryawan - $hadirHariIni - $izinHariIni;

            $persentaseKehadiran = $totalKaryawan > 0
                ? round(($hadirHariIni / $totalKaryawan) * 100, 1)
                : 0;

            // 5. Grafik Mingguan
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $weeklyData = Attendance::selectRaw('tanggal, COUNT(DISTINCT pin) as total_hadir')
                ->whereBetween('tanggal', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->where('io', 'in')
                ->whereIn('pin', $pinAktif) // HANYA karyawan aktif
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'ASC')
                ->get();

            $labels = [];
            $dataHadir = [];

            $period = CarbonPeriod::create($startOfWeek, $endOfWeek);

            foreach ($period as $date) {
                $labels[] = $date->translatedFormat('l');
                $record = $weeklyData->firstWhere('tanggal', $date->format('Y-m-d'));
                $dataHadir[] = $record ? $record->total_hadir : 0;
            }

            // tambahan
            $totalDepartemen = Department::count();
            $hariKerjaBulanIni = 0;
            $terlatHariIni = 0;

            return view('dashboard.super-admin', compact(
                'totalKaryawan',
                'hadirHariIni',
                'izinHariIni',
                'alfaHariIni',
                'persentaseKehadiran',
                'totalDepartemen',
                'hariKerjaBulanIni',
                'terlatHariIni',
                'labels',
                'dataHadir'
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
