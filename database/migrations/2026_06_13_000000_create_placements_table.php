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
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null'); 
            
            // --- INI KOLOM BARU AGAR SISTEM INGAT SLOT MANA YANG DIAMBIL ---
            $table->foreignId('company_slot_id')->nullable()->constrained('company_slots')->onDelete('set null');
            // ---------------------------------------------------------------
            
            $table->float('final_smart_score'); 
            $table->enum('placement_method', ['SYSTEM', 'MANUAL_OVERRIDE'])->default('SYSTEM');
            $table->text('notes')->nullable(); 
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};