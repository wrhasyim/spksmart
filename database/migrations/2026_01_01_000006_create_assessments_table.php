<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            // Relasi sekarang mengarah ke tabel students, bukan users
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); 
            
            // 5 Kriteria Utama SMART (Nilai 0-100)
            $table->float('absensi');        // Benefit
            $table->float('fisik_mental');   // Benefit
            $table->float('keaktifan');      // Benefit
            $table->float('catatan_kasus');   // Cost
            $table->float('administrasi');    // Benefit
            
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};