<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            // Relasi mengarah ke tabel students
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Jika NULL, berarti siswa gagal lolos syarat manapun -> Masuk Program Pembinaan
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null'); 
            
            $table->float('final_smart_score'); // Skor akhir persentase 0-100
            $table->enum('placement_method', ['SYSTEM', 'MANUAL_OVERRIDE'])->default('SYSTEM');
            $table->text('notes')->nullable(); // Catatan pembinaan atau veto manual
            
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};