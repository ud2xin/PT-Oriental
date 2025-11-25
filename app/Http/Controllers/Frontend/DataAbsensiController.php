<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->input('bulan', date('m'));
        $tahun  = $request->input('tahun', date('Y'));
        $search = $request->input('search');

        $startDate = "$tahun-$bulan-01";
        $endDate   = date("Y-m-t", strtotime($startDate));

        $query = DB::table('TK_TRANST AS A')
            ->leftJoin('TH_EMP01M AS B', 'A.EMPL_NMBR', '=', 'B.EMPL_NMBR')
            ->leftJoin('TB_CODEXD AS D', function ($join) {
                $join->on('B.DRPT_CODE', '=', 'D.codd_valu')
                     ->where('D.codh_flnm', '=', 'DRPT_CODE');
            })
            ->leftJoin('TB_CODEXD AS C', function ($join) {
                $join->on('B.DUPL_DRPT', '=', 'C.codd_valu')
                     ->where('C.codh_flnm', '=', 'DIVX_CODE');
            })
            ->select(
                'A.EMPL_NMBR',
                'B.KORX_NAME',
                'C.codd_desc AS DIVX_NAME',
                'D.codd_desc AS DEPT_NAME',
                DB::raw("'' AS GROUP_NAME"),
                'A.TYPE_CODE',
                DB::raw("CONVERT(VARCHAR, A.TRNS_DATE, 120) AS TRNS_DATE"),
                'A.TERM_NMBR',
                'A.TRAN_USR1'
            )
            ->whereBetween(DB::raw("CONVERT(date, A.TRNS_DATE)"), [$startDate, $endDate]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('A.EMPL_NMBR', 'LIKE', "%$search%")
                  ->orWhere('B.KORX_NAME', 'LIKE', "%$search%")
                  ->orWhere('C.codd_desc', 'LIKE', "%$search%")
                  ->orWhere('D.codd_desc', 'LIKE', "%$search%");
            });
        }

        $absensi = $query->orderBy('A.TRNS_DATE', 'asc')->paginate(20);

        return view('attendance.index', compact('absensi', 'bulan', 'tahun', 'search'));
    }
}
