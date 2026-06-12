<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // absensi, fisik, aktif, kasus, admin
            $table->string('name');
            $table->decimal('weight', 3, 2); // Skala desimal 0.00 s.d 1.00
            $table->string('type'); // 'benefit' atau 'cost'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};