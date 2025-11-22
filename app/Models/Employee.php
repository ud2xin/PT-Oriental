<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Employee extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'employees'; // ubah jika pakai schema: 'hr.employees'

    /**
     * Primary Key
     * Jika NIP adalah PK → set di sini
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;
    
    protected $fillable = [
        'nama',
        'notelp',
        'jabatan',
        'departemen_id',
        'status',
        'nip',
    ];

    /**
     * Relasi ke Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
