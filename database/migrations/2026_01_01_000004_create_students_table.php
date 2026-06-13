<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('name');
            $table->string('class_name')->nullable(); // XII TKJ 1
            $table->foreignId('major_id')->constrained('majors')->onDelete('restrict');
            $table->enum('gender', ['L', 'P']);
            $table->string('parent_phone', 20)->nullable(); // Nomor WA Ortu
            
            // Status SPK Lengkap
            $table->enum('status', [
                'belum_prakerin', 
                'rekomendasi', 
                'waiting_list', 
                'pembinaan', 
                'lolos_prakerin', 
                'mengundurkan_diri'
            ])->default('belum_prakerin');
            
            $table->decimal('final_score', 6, 2)->nullable();
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('students'); }
};