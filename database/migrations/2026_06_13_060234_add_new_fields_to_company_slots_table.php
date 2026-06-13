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
        Schema::table('company_slots', function (Blueprint $table) {
            // Menambahkan kolom-kolom baru yang dibutuhkan sistem
            if (!Schema::hasColumn('company_slots', 'batch_name')) {
                $table->string('batch_name')->after('academic_year_id')->nullable();
                $table->string('gender_requirement', 20)->default('Semua')->after('batch_name');
                $table->integer('quota')->default(0)->after('gender_requirement');
                $table->float('min_total_score')->default(0)->after('quota');
                $table->float('min_absensi_score')->default(0)->after('min_total_score');
                $table->date('start_date')->nullable()->after('min_absensi_score');
                $table->date('end_date')->nullable()->after('start_date');
            }

            // Jika Anda sudah tidak memakai kuota_male & quota_female, Anda bisa menghapusnya.
            // Namun agar aman dari error sisa data lama, kita biarkan saja atau beri komentar di bawah ini:
            // $table->dropColumn(['quota_male', 'quota_female']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_slots', function (Blueprint $table) {
            $table->dropColumn([
                'batch_name',
                'gender_requirement',
                'quota',
                'min_total_score',
                'min_absensi_score',
                'start_date',
                'end_date'
            ]);
        });
    }
};