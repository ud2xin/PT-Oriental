<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $tglParam = str_replace('-', '', $tanggal);

        // Jalankan Stored Procedure
        $data = DB::connection('sqlsrv')->select("
            EXEC sph_AttAbsenDaly_1 ?, ?, ?, ?, ?
        ", [
            $tglParam,
            '',
            '',
            '',
            ''
        ]);

        // Ubah ke collection
        $collection = collect($data);

        // PAGINATION
        $perPage = 20; // jumlah per halaman
        $page = LengthAwarePaginator::resolveCurrentPage();
        $items = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $daily = new LengthAwarePaginator(
            $items,
            $collection->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return view('reports.index', compact('tanggal', 'daily'));
    }
}
