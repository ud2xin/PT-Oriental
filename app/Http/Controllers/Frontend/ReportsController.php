<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf; // Penting untuk PDF
use App\Models\Department;      // Penting untuk Filter Departemen

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $tglParam = str_replace('-', '', $tanggal);

        // Ambil data departemen untuk dropdown filter (Biar tidak error/kosong)
        $departemenList = Department::all();

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

        // Filter by Departemen jika ada input dari user
        if ($request->filled('department_id')) {
            $collection = $collection->where('dept_id', $request->department_id); // Sesuaikan 'dept_id' dengan nama kolom ID dept di SP
        }

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

        // Kirim semua variabel ke View
        return view('reports.index', compact('tanggal', 'daily', 'departemenList'));
    }

    public function export(Request $request)
    {
        $tanggal = $request->tanggal ?? date('Y-m-d');
        $tglParam = str_replace('-', '', $tanggal);

        // Jalankan SP (Sama persis dengan index)
        $data = DB::connection('sqlsrv')->select("
            EXEC sph_AttAbsenDaly_1 ?, ?, ?, ?, ?
        ", [
            $tglParam,
            '',
            '',
            '',
            ''
        ]);
        
        $collection = collect($data);

        // Filter by Departemen di PDF juga (opsional, biar sinkron sama tampilan)
        if ($request->filled('department_id')) {
            $collection = $collection->where('dept_id', $request->department_id);
        }

        // Load View PDF (Pastikan nama file view sesuai: reports/pdf-daily.blade.php)
        $pdf = Pdf::loadView('reports.pdf-daily', [
            'data' => $collection, // Kirim data yang sudah jadi collection
            'tanggal' => $tanggal
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Harian_' . $tanggal . '.pdf');
    }
}