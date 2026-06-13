<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('company_slot_major', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_slot_id')->constrained('company_slots')->onDelete('cascade');
            $table->foreignId('major_id')->constrained('majors')->onDelete('restrict');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('company_slot_major'); }
};