<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function checkin()
    {
        return view('attendance.checkin');
    }

    public function checkout()
    {
        return view('attendance.checkout');
    }
}
