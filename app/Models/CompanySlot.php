<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySlot extends Model
{
    use HasFactory, SoftDeletes;

    // Tambahkan semua kolom baru ke dalam array fillable ini
    protected $fillable = [
        'company_id',
        'academic_year_id',
        'major_id', // Pastikan major_id juga masuk!
        'batch_name',
        'gender_requirement',
        'quota',
        'min_total_score',
        'min_absensi_score',
        'start_date',
        'end_date',
        'quota_male',   
        'quota_female', 
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}