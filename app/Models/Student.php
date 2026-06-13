<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Jika Anda menggunakan SoftDeletes, uncomment baris di bawah ini dan tambahkan di dalam class
// use Illuminate\Database\Eloquent\SoftDeletes; 

class Student extends Model
{
    use HasFactory; 
    // use SoftDeletes;

    protected $fillable = [
        'nisn',
        'name',
        'class_name',
        'major_id',
        'gender',
        'parent_phone',
        'status',
        'academic_year_id'
    ];

    /**
     * Relasi ke tabel Jurusan (Major)
     */
    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    /**
     * Relasi ke tabel Tahun Ajaran (AcademicYear)
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * Relasi ke tabel Penilaian (Assessment)
     * Asumsi: 1 Siswa memiliki 1 Penilaian di tahun ajaran aktif
     */
    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }

    /**
     * RELASI BARU YANG DIBUTUHKAN MESIN SPK
     * Relasi ke tabel Penempatan (Placement)
     * Asumsi: 1 Siswa ditempatkan di 1 Industri
     */
    public function placement()
    {
        return $this->hasOne(Placement::class);
    }
}