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

        // DEPARTEMEN (karena tidak ada data departemen)
        $departemenList = [
            'all' => 'Semua Departemen'
        ];

        // QUERY ABSENSI
        $query = AttendanceLog::whereBetween(
            DB::raw("CAST(tanggal AS DATE)"),
            [$tanggalAwal, $tanggalAkhir]
        );

        // PROSES (IN/OUT PER KARYAWAN PER TANGGAL)
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
            ->orderBy('tanggal', 'asc')
            ->get();

        // REKAP
        $rekap = [];

        foreach ($absensi as $row) {
            $rekap[] = [
                'tanggal'  => $row->tanggal,
                'nip'      => $row->nip,
                'nama'     => $row->nama,
                'bagian'   => $row->jabatan,
                'dept'     => $row->departemen ?: '-',
                'group'    => '-',
                'in'       => $row->jam_masuk ?? '-',
                'out'      => $row->jam_keluar ?? '-',
                'status'   => $row->jam_masuk ? 'Hadir' : 'Alfa',
                'overtime' => '-',
                'alasan'   => '-',
                'remark'   => '-',
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
            'departemen'     => 'all',
            'departemenList' => $departemenList,
        ]);
    }
}
