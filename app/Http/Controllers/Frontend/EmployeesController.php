<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = DB::select("EXEC SPH_KaryawanList_1 '', '', '', '', ''");
        $employees = collect($employees)->paginate(10);
        return view('dashboard.employees.index', compact('employees'));
    }
}
