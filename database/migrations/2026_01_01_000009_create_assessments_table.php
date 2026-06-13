<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('absensi')->nullable();
            $table->integer('fisik')->nullable();
            $table->integer('keaktifan')->nullable();
            $table->integer('kasus')->nullable();
            $table->integer('administrasi')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assessments'); }
};