<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    public function index(Request $request)
    {
        $logs = AttendanceLog::orderBy('tanggal_scan', 'asc')->get();

        // Kelompokkan per karyawan per tanggal
        $grouped = $logs->groupBy(function ($q) {
            return $q->nip . '_' . $q->tanggal;
        });

        $overtimeData = [];

        foreach ($grouped as $rows) {

            $first = $rows->first();
            $last  = $rows->last();

            $tanggal = $first->tanggal;
            $nip     = $first->nip;
            $nama    = $first->nama;

            // Ambil jam masuk dan keluar
            $jamMasuk  = Carbon::parse($first->tanggal . ' ' . $first->jam);
            $jamKeluar = Carbon::parse($last->tanggal . ' ' . $last->jam);

            // ================================
            // 1. TENTUKAN SHIFT DARI JAM MASUK
            // ================================
            $shift = null;

            $jm = $jamMasuk->format('H:i:s');

            if ($jm >= '04:00:00' && $jm <= '11:00:00') {
                $shift = 'A';
            } elseif ($jm >= '12:00:00' && $jm <= '19:00:00') {
                $shift = 'B';
            } else {
                // jam malam + dini hari
                $shift = 'C';
            }

            // ================================
            // 2. TENTUKAN JAM SHIFT
            // ================================
            $shiftSchedule = [
                'A' => ['start' => '08:00:00', 'end' => '16:00:00'],
                'B' => ['start' => '16:00:00', 'end' => '00:00:00'],
                'C' => ['start' => '00:00:00', 'end' => '08:00:00'],
            ];

            $shiftStart = Carbon::parse($tanggal . ' ' . $shiftSchedule[$shift]['start']);
            $shiftEnd   = Carbon::parse($tanggal . ' ' . $shiftSchedule[$shift]['end']);

            // Jika shift berakhir lewat tengah malam
            if ($shiftEnd <= $shiftStart) {
                $shiftEnd->addDay();
            }

            // =================================
            // 3. HITUNG LEMBUR
            // =================================
            $overtimeHours = 0;

            if ($jamKeluar->greaterThan($shiftEnd)) {
                $overtimeHours = $shiftEnd->diffInHours($jamKeluar);
            }

            $overtimeData[] = [
                'tanggal'     => $tanggal,
                'nip'         => $nip,
                'nama'        => $nama,
                'shift'       => $shift,
                'jam_masuk'   => $jamMasuk->format('H:i:s'),
                'jam_keluar'  => $jamKeluar->format('H:i:s'),
                'overtime'    => $overtimeHours,
            ];
        }

        return view('reports.overtime', compact('overtimeData'));
    }
}
