<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            // Nullable karena bisa masuk waiting list/pembinaan
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('company_slot_id')->nullable()->constrained('company_slots')->onDelete('set null');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            
            $table->decimal('final_smart_score', 6, 2)->nullable();
            $table->string('placement_method')->default('SYSTEM');
            
            // Status Tahapan (ACC)
            $table->enum('status_pencocokan', [
                'rekomendasi', 
                'final', 
                'waiting_list', 
                'pembinaan'
            ])->default('rekomendasi');
            
            // Audit Trail Intervensi Manual
            $table->boolean('is_manual_override')->default(false);
            $table->text('override_reason')->nullable();
            
            // Jejak detail kalkulasi SPK (Kenapa gagal dll)
            $table->text('notes')->nullable(); 
            
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('placements'); }
};