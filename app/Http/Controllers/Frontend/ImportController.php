<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function preview(Request $request)
    {
        // nanti untuk preview data sebelum diimport
    }

    public function process(Request $request)
    {
        // nanti untuk proses penyimpanan data ke database
    }
}
