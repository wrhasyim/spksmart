<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // HANYA MEMBUAT TABEL COMPANY_SLOTS
        Schema::create('company_slots', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');
            
            $table->string('batch_name'); 
            $table->integer('quota');
            $table->decimal('min_total_score', 5, 2)->default(0.00); 
            $table->integer('min_absensi_score')->default(0); 
            
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_slots');
    }
};