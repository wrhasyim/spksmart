<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->integer('quota');
            
            // Hard Filters
            $table->foreignId('major_id')->constrained('majors')->onDelete('cascade');
            $table->enum('gender_requirement', ['L', 'P', 'ALL'])->default('ALL');
            
            // Passing Grades (Batas Minimum Nilai SMART untuk tiap perusahaan)
            $table->integer('min_total_score')->default(0);
            $table->integer('min_absensi_score')->default(0);
            $table->integer('min_fisik_score')->default(0);
            $table->integer('min_keaktifan_score')->default(0);
            $table->integer('min_administrasi_score')->default(0);
            
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};