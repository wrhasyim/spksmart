<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'phone'
    ];

    // Relasi: Satu perusahaan bisa memiliki banyak slot gelombang
    public function slots()
    {
        return $this->hasMany(CompanySlot::class);
    }

    // Relasi: Satu perusahaan bisa memiliki banyak penempatan siswa
    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}