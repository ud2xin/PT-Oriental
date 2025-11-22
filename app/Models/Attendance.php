<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Force MSSQL connection
     */
    protected $connection = 'sqlsrv';

    /**
     * Table with schema
     */
    protected $table = 'ts.attendance_logs';

    /**
     * MSSQL table tidak pakai timestamp Laravel
     */
    public $timestamps = false;

    /**
     * Primary key tidak ada / bukan auto increment
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * Mass assignable fields
     */
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

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope filter tanggal
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    /**
     * Scope scan masuk (I/O = 1)
     */
    public function scopeMasuk($query)
    {   
        return $query->where('io', 1);
    }

    /**
     * Scope scan keluar (I/O = 2)
     */
    public function scopeKeluar($query)
    {
        return $query->where('io', 2);
    }
}
