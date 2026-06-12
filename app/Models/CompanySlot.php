<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySlot extends Model
{
    use HasFactory;

    // Ini sangat penting! Laravel butuh izin ini untuk memasukkan data dari Seeder
    protected $fillable = [
        'company_id', 
        'academic_year_id', 
        'major_id', 
        'batch_name', 
        'gender_requirement', // <--- Tambahkan ini
        'quota', 
        'min_total_score', 
        'min_absensi_score',
        'start_date', 
        'end_date'
    ];

    // Lindungi tipe data tanggal agar otomatis menjadi objek Carbon
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relasi ke tabel master perusahaan
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Relasi ke tahun ajaran
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    // Relasi ke jurusan
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}