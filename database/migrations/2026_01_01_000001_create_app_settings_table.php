<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('instansi_atas')->nullable(); // PEMERINTAH PROVINSI...
            $table->string('nama_sekolah')->default('SMK Negeri 1');
            $table->text('alamat_sekolah')->nullable();
            $table->string('kontak_sekolah')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->text('teks_pengantar_surat')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('app_settings'); }
};