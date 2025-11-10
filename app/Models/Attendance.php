<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance_logs';

    protected $fillable = [
        'user_id', 'tanggal_scan', 'tanggal', 'jam', 'pin', 'nip', 'nama',
        'jabatan', 'departemen', 'kantor', 'verifikasi', 'io', 'workcode',
        'sn', 'mesin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
