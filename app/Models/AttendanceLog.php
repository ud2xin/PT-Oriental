<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    // Koneksi SQL Server
    protected $connection = 'sqlsrv';

    // Tabel TANPA schema — Laravel akan tambahkan schema otomatis dari config
    protected $table = 'attendance_logs';

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_scan',
        'tanggal',
        'jam',
        'pin',
        'nip',
        'nama',
        'jabatan',
        'departemen',
        'kantor',
        'verifikasi',
        'io',
        'workcode',
        'sn',
        'mesin'
    ];
}
