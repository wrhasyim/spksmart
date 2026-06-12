<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Placement extends Model
{
    protected $fillable = [
        'student_id',
        'company_id',
        'company_slot_id', // <--- TAMBAHAN BARU UNTUK MENCATAT SLOT
        'final_smart_score',
        'placement_method',
        'notes',
        'academic_year_id',
    ];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function companySlot(): BelongsTo { return $this->belongsTo(CompanySlot::class); } // <--- RELASI KE SLOT
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}