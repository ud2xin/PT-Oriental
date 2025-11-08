<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'department_id',
        'phone',
        'position',
    ];

    // Relasi ke departemen
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
