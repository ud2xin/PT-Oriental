<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // FIX SCHEMA + TABLE
    public function getTable()
    {
        return 'ts.attendance_logs';
    }

    protected $fillable = [
        'user_id',
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
        'mesin',
    ];

    public function user()
    {
    return $this->belongsTo(User::class, 'user_id');
    }


    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeMasuk($query)
    {   
        return $query->where('io', 1);
    }

    public function scopeKeluar($query)
    {
        return $query->where('io', 2);
    }
}
