<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // FILTER
        $tanggalAwal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());
        $departemen   = $request->input('departemen', 'all');

        // DEPARTEMEN LIST
        $departemenList = AttendanceLog::select('departemen')
            ->distinct()
            ->pluck('departemen', 'departemen')
            ->prepend('Semua Departemen', 'all');

        // QUERY ABSENSI
        $query = AttendanceLog::whereBetween(DB::raw("CAST(tanggal AS DATE)"), [$tanggalAwal, $tanggalAkhir]);

        if ($departemen !== 'all') {
            $query->where('departemen', $departemen);
        }

        // AMBIL DATA (Group by per karyawan per tanggal)
        $absensi = $query
            ->select(
                'nip',
                'nama',
                'jabatan',
                'departemen',
                'tanggal',
                DB::raw("MIN(CASE WHEN io = 1 THEN jam END) AS jam_masuk"),
                DB::raw("MAX(CASE WHEN io = 2 THEN jam END) AS jam_keluar")
            )
            ->groupBy('nip', 'nama', 'jabatan', 'departemen', 'tanggal')
            ->orderBy('tanggal')
            ->paginate(20)
            ->appends($request->all()); // supaya filter tidak hilang

        // REKAP OUTPUT
        $rekap = [];

        foreach ($absensi as $a) {

            // === HITUNG OVERTIME ===
            $overtime = "-";

            if ($a->jam_masuk && $a->jam_keluar && $a->jam_keluar !== '00:00:00') {

                $jamMasuk  = Carbon::parse($a->jam_masuk);
                $jamKeluar = Carbon::parse($a->jam_keluar);

                // Total menit kerja
                $totalMenit = $jamMasuk->diffInMinutes($jamKeluar);

                // Standard kerja (8 jam = 480 menit)
                $normalMenit = 480;

                if ($totalMenit > $normalMenit) {

                    $lembur = $totalMenit - $normalMenit;
                    $jam    = floor($lembur / 60);
                    $menit  = $lembur % 60;

                    // Format HH:MM
                    $overtime = sprintf('%d:%02d', $jam, $menit);
                } else {
                    $overtime = "0:00";
                }
            }

            // === STATUS HADIR ===
            $status = $a->jam_masuk ? "Hadir" : "Alfa";

            // Masukkan ke tabel rekap
            $rekap[] = [
                'tanggal'  => $a->tanggal,
                'nip'      => $a->nip,
                'nama'     => $a->nama,
                'bagian'   => $a->jabatan,
                'dept'     => $a->departemen,
                'group'    => "-",
                'in'       => $a->jam_masuk,
                'out'      => $a->jam_keluar,
                'status'   => $status,
                'overtime' => $overtime,
                'alasan'   => "-",
                'remark'   => "-",
            ];
        }

        // Query dasar untuk summary
        $summaryQuery = AttendanceLog::whereBetween(DB::raw("CAST(tanggal AS DATE)"), [$tanggalAwal, $tanggalAkhir]);

        if ($departemen !== 'all') {
            $summaryQuery->where('departemen', $departemen);
        }

        // Summary full data (tanpa paginate)
        $summaryTotal = $summaryQuery
            ->select(
                'nip',
                'tanggal',
                DB::raw("MIN(CASE WHEN io = 1 THEN jam END) AS jam_masuk")
            )
            ->groupBy('nip', 'tanggal')
            ->get();

        // Summary values
        $summary = [
            'total_karyawan' => $summaryTotal->groupBy('nip')->count(),
            'total_hadir'    => $summaryTotal->where('jam_masuk', '!=', null)->count(),
            'total_izin'     => 0,
            'total_alfa'     => 0,
        ];


        return view('reports.index', [
            'absensi'        => $absensi,
            'summary'        => $summary,
            'tanggalAwal'    => $tanggalAwal,
            'tanggalAkhir'   => $tanggalAkhir,
            'departemen'     => $departemen,
            'departemenList' => $departemenList,
            'rekap'          => $rekap, // <----- FIX DISINI
        ]);
    }
}
