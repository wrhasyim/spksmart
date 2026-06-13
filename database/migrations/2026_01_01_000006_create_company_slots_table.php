<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('company_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->string('batch_name'); // Misal: Gelombang 1
            $table->integer('quota');
            $table->enum('gender_requirement', ['L', 'P', 'Semua']);
            $table->decimal('min_total_score', 5, 2)->default(0);
            $table->integer('min_absensi_score')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('company_slots'); }
};