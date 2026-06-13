<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Kolom JSON dinamis untuk menampung semua nilai (Format: {"absensi": 85, "fisik": 90})
            $table->json('scores_data'); 
            
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};