<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('company_slots', function (Blueprint $table) {
            // Mengubah major_id agar boleh kosong (nullable)
            $table->unsignedBigInteger('major_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('company_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('major_id')->nullable(false)->change();
        });
    }
};