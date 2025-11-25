<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeesController extends Controller
{
    public function index()
    {
        // Dropdown Static Data
        $jabatanList = [
            "MANAGER","SUPERVISOR","STAFF OFFICE","STAFF HRD/GA","OPERATOR PRODUKSI","FOREMAN",
            "Material","Adm. PRODUKSI","Maintenance","Admin maintenance","Adm PE","LEADER",
            "Support","MR","Quality Control","Cleaning service","DRIVER","Security"
        ];

        $departemenList = [
            "OFFICE","ACCOUNTING","HRD/GA","PURCHASING","MTA","IT","MAINTENANCE","P.E",
            "EX-IM","WIP PCB","WIP IMPELLER","WIP CORE","FAN ASSY","QC",
            "CLEANING SERVICE","DRIVER","SECURITY"
        ];

        $ptkpList = ["K2","TK","K0","K1","K3","K4"];
        $pendidikanList = ["SMA/SMK","S1","D3","PAKET C","MA","D1","NONE","STM"];

        // GET DATA SP
        $results = DB::select("EXEC SPH_KaryawanList_1 ?, ?, ?, ?, ?", ['', '', '', '', '']);
        $collection = collect($results);

        // FILTER
        if (request('gender')) {
            $collection = $collection->where('SEXX_PART', request('gender'));
        }

        if (request('status')) {
            $collection = $collection->where('MARY_PART', request('status'));
        }

        if (request('jabatan')) {
            $collection = $collection->where('JABATAN', request('jabatan'));
        }

        if (request('departemen')) {
            $collection = $collection->where('dept_name', request('departemen'));
        }

        if (request('ptkp')) {
            $collection = $collection->where('IPSA_PART', request('ptkp'));
        }

        if (request('pendidikan')) {
            $collection = $collection->where('BDAY_PART', request('pendidikan'));
        }

        if (request('search')) {
            $search = strtolower(request('search'));
            $collection = $collection->filter(function ($item) use ($search) {
                return false !== stristr($item->korx_name, $search)
                    || false !== stristr($item->empl_nmbr, $search)
                    || false !== stristr($item->JABATAN, $search)
                    || false !== stristr($item->dept_name, $search);
            });
        }

        // PAGINATION
        $page = request()->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $employees = new LengthAwarePaginator(
            $collection->slice($offset, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard.employees.index', compact(
            'employees','jabatanList','departemenList','ptkpList','pendidikanList'
        ));
    }
}
