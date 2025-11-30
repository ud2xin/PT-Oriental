<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        // tanggal diambil dari query string
        $tanggal = $request->query('tanggal');

        // jika belum memilih tanggal → tampilkan halaman kosong
        if (!$tanggal) {
            return view('reports.overtime', [
                'tanggal' => null,
                'data' => null
            ]);
        }

        // generate report pakai tanggal dari query
        return $this->generateReport($tanggal, $request);
    }

    public function filter(Request $request)
    {
        $request->validate(['tanggal' => 'required|date']);

        // redirect GET agar pagination tetap bekerja
        return redirect()->route('reports.overtime.index', [
            'tanggal' => $request->tanggal
        ]);
    }

    private function generateReport($tanggal, Request $request)
    {
        $periode = date('Ym', strtotime($tanggal));

        $raw = DB::select("EXEC sph_AttAbsenDaly_3 ?, '', '', '', ''", [$periode]);

        // --- PIVOT ---
        $pivot = [];

        foreach ($raw as $row) {
            $nik = $row->empl_nmbr;

            if (!isset($pivot[$nik])) {
                $pivot[$nik] = [
                    'nik' => $nik,
                    'nama' => $row->korx_name,
                    'dept' => $row->dept_name,
                    'total_ot' => 0,
                ];

                for ($i = 21; $i <= 31; $i++) $pivot[$nik][$i] = 0;
                for ($i = 1; $i <= 20; $i++) $pivot[$nik][$i] = 0;
            }

            $hari = (int) date('d', strtotime($row->GUNT_DATE));
            $pivot[$nik][$hari] = $row->OVER_CONV;
            $pivot[$nik]['total_ot'] += $row->OVER_CONV;
        }

        $collection = collect(array_values($pivot));

        // --- PAGINATION ---
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $pageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $pageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => route('reports.overtime.index'),
                'query' => ['tanggal' => $tanggal] // penting!
            ]
        );

        return view('reports.overtime', [
            'data' => $paginator,
            'tanggal' => $tanggal
        ]);
    }
}
