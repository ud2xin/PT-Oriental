<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee; // <-- Pastikan ini Employee
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
        // Kita tetap baca 'pin' dari CSV, karena NIP dan PIN di CSV sama
        $pin = $row['pin'] ?? null; 

        if (!isset($row['tanggal_scan']) || empty($row['tanggal_scan']) || !$pin) {
            Log::warning('Data baris dilewati (PIN atau Tanggal Scan kosong)', $row);
            return null; 
        }
        
        Log::info("Mencari KARYAWAN (di tabel employees) untuk PIN/NIP: " . $pin);
        
        // --- INI PERBAIKANNYA ---
        // Cari di tabel 'employees' menggunakan kolom 'nip'
        $employee = Employee::where('nip', $pin)->first(); 
        
        $userId = null; 
        
        if (!$employee) {
            Log::error("Karyawan TIDAK DITEMUKAN untuk NIP: " . $pin . " (Nama di CSV: " . $row['nama'] . ")");
        } else {
            $userId = $employee->user_id; 
            Log::info("Karyawan ditemukan. NIP: " . $pin . " -> User ID: " . $userId);
        }
        
        try {
            $tanggalScan = Carbon::createFromFormat('d-m-Y H:i:s', $row['tanggal_scan'])->toDateTimeString();
            $tanggal = Carbon::createFromFormat('d-m-Y H:i:s', $row['tanggal_scan'])->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Error parsing date: ' . $row['tanggal_scan']);
            return null; 
        }

        return new Attendance([
            'user_id'      => $userId, // <-- ID User dari tabel employees
            'tanggal_scan' => $tanggalScan,
            'tanggal'      => $tanggal,
            'jam'          => $row['jam'] ?? null,
            'pin'          => $pin,
            'nip'          => $row['nip'] ?? null,
            'nama'         => $row['nama'] ?? null,
            'jabatan'      => $row['jabatan'] ?? null,
            'departemen'   => $row['departemen'] ?? null,
            'kantor'       => $row['kantor'] ?? null,
            'verifikasi'   => $row['verifikasi'] ?? null,
            'io'           => $row['i_o'] ?? $row['io'] ?? null,
            'workcode'     => $row['workcode'] ?? null,
            'sn'           => $row['sn'] ?? null,
            'mesin'        => $row['mesin'] ?? null,
        ]);
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