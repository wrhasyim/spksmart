<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Placement extends Model
{
    // Mengizinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'student_id',
        'company_id', // Pastikan ini company_id
        'final_smart_score',
        'placement_method',
        'notes',
        'academic_year_id',
    ];

    /**
     * Relasi ke Tabel Siswa
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke Tabel Perusahaan
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relasi ke Tabel Tahun Ajaran
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}