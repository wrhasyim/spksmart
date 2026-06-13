<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySlot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 
        'academic_year_id', 
        // 'major_id', <--- DIHAPUS, KARENA SEKARANG PAKAI PIVOT / RELASI MAJORS()
        'batch_name', 
        'gender_requirement', // Wajib ada untuk filter jenis kelamin
        'quota', 
        'min_total_score', 
        'min_absensi_score',
        'start_date', 
        'end_date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Relasi Multi-Jurusan (Pivot)
    public function majors()
    {
        return $this->belongsToMany(Major::class, 'company_slot_major');
    }

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}