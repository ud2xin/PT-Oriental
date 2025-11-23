<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AttendanceLog;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // FILTER
        $tanggalAwal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->toDateString());
        $departemen   = $request->input('departemen', 'all');

        // LIST DEPARTEMEN DARI ABSENSI
        $departemenList = AttendanceLog::select('departemen')
            ->distinct()
            ->pluck('departemen', 'departemen')
            ->prepend('Semua Departemen', 'all');

        // AMBIL ABSENSI
        $query = AttendanceLog::whereBetween(DB::raw("CAST(tanggal AS DATE)"), [$tanggalAwal, $tanggalAkhir]);

        if ($departemen !== 'all') {
            $query->where('departemen', $departemen);
        }

        // PROSES ABSENSI (IN/OUT PER TANGGAL)
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
            ->get();

        // REKAP
        $rekap = [];

        foreach ($absensi as $a) {
            $rekap[] = [
                'tanggal' => $a->tanggal,
                'nip'     => $a->nip,
                'nama'    => $a->nama,
                'bagian'  => $a->jabatan,
                'dept'    => $a->departemen,
                'group'   => '-',
                'in'      => $a->jam_masuk ?? '-',
                'out'     => $a->jam_keluar ?? '-',
                'status'  => $a->jam_masuk ? 'Hadir' : 'Alfa',
                'overtime'=> '-',
                'alasan'  => '-',
                'remark'  => '-',
            ];
        }

        // SUMMARY
        $summary = [
            'total_karyawan' => $absensi->groupBy('nip')->count(),
            'total_hadir'    => $absensi->count(),
            'total_izin'     => 0,
            'total_alfa'     => 0,
        ];

        return view('reports.index', [
            'rekap'          => $rekap,
            'summary'        => $summary,
            'tanggalAwal'    => $tanggalAwal,
            'tanggalAkhir'   => $tanggalAkhir,
            'departemen'     => $departemen,
            'departemenList' => $departemenList,
        ]);
    }
}
