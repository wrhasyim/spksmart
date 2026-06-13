<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            
            // INI KOLOM YANG HILANG (MAJOR_ID)
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();
            
            $table->string('batch_name')->nullable();
            $table->string('gender_requirement', 20)->default('Semua');
            $table->integer('quota')->default(0);
            
            // Kolom pendukung SPK (Dihasilkan dari Controller)
            $table->integer('quota_male')->default(0);
            $table->integer('quota_female')->default(0);

            $table->float('min_total_score')->default(0);
            $table->float('min_absensi_score')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_slots');
    }
};