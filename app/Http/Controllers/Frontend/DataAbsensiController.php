<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DataAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $search  = $request->input('search');

        // ===============================
        //  JIKA BELUM PILIH TANGGAL → KIRIM PAGINATOR KOSONG
        // ===============================
        if (!$tanggal) {

            $emptyPaginator = new LengthAwarePaginator(
                [],  // data kosong
                0,   // total
                20,  // perPage
                1,   // currentPage
                ['path' => url()->current()]
            );

            return view('attendance.index', [
                'absensi' => $emptyPaginator,
                'tanggal' => null,
                'search'  => $search
            ]);
        }

        // ===============================
        //  QUERY DATA ABSENSI BERDASARKAN TANGGAL
        // ===============================
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
            ->whereDate('A.TRNS_DATE', '=', $tanggal);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('A.EMPL_NMBR', 'LIKE', "%$search%")
                    ->orWhere('B.KORX_NAME', 'LIKE', "%$search%")
                    ->orWhere('C.codd_desc', 'LIKE', "%$search%")
                    ->orWhere('D.codd_desc', 'LIKE', "%$search%");
            });
        }

        $absensi = $query
            ->orderBy('A.TRNS_DATE', 'asc')
            ->paginate(20)
            ->appends($request->query());

        return view('attendance.index', compact('absensi', 'tanggal', 'search'));
    }
}
