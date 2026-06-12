<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySlot extends Model
{
    protected $fillable = [
        'company_id', 
        'academic_year_id', 
        'major_id', 
        'batch_name', 
        'gender_requirement', // <--- INI WAJIB ADA AGAR JENIS KELAMIN TERSIMPAN!
        'quota', 
        'min_total_score', 
        'min_absensi_score',
        'start_date', 
        'end_date'
    ];
public function placements(): HasMany
    {
        // Menyambungkan slot ini ke tabel placements berdasarkan ID slot
        return $this->hasMany(Placement::class, 'company_slot_id');
    }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
    public function major(): BelongsTo { return $this->belongsTo(Major::class); }
}