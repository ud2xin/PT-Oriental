<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        $pin = $row['pin'] ?? null;

        if (!isset($row['tanggal_scan']) || empty($row['tanggal_scan']) || !$pin) {
            return null;
        }

        // Cari employee berdasarkan NIP
        $employee = Employee::where('nip', $pin)->first();
        $userId   = $employee->user_id ?? null;

        // Parse tanggal & waktu
        try {
            $tanggalScan = Carbon::createFromFormat('d-m-Y H:i:s', $row['tanggal_scan'])->toDateTimeString();
            $tanggal     = Carbon::createFromFormat('d-m-Y H:i:s', $row['tanggal_scan'])->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Tanggal Scan tidak valid: ' . ($row['tanggal_scan'] ?? 'NULL'));
            return null;
        }

        // 🛑 ANTI DUPLIKAT
        return Attendance::firstOrCreate(
            [
                // Field yang dianggap SATU DATA YANG SAMA
                'pin'      => $pin,
                'tanggal'  => $tanggal,
                'jam'      => $row['jam'] ?? null,
                'io'       => $row['i_o'] ?? $row['io'] ?? null,
                'sn'       => $row['sn'] ?? null,
            ],
            [
                'user_id'      => $userId,
                'tanggal_scan' => $tanggalScan,
                'nip'          => $row['nip'] ?? null,
                'nama'         => $row['nama'] ?? null,
                'jabatan'      => $row['jabatan'] ?? null,
                'departemen'   => $row['departemen'] ?? null,
                'kantor'       => $row['kantor'] ?? null,
                'verifikasi'   => $row['verifikasi'] ?? null,
                'workcode'     => $row['workcode'] ?? null,
                'mesin'        => $row['mesin'] ?? null,
            ]
        );
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
