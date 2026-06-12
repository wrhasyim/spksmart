<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criterion;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        Criterion::truncate();

        Criterion::insert([
            ['code' => 'absensi',       'name' => 'Nilai Absensi', 'weight' => 0.30, 'type' => 'benefit'],
            // --- NAMA 'CODE' DI BAWAH INI KITA SESUAIKAN DENGAN NAMA KOLOM DI DATABASE ---
            ['code' => 'fisik_mental',  'name' => 'Fisik & Mental', 'weight' => 0.15, 'type' => 'benefit'],
            ['code' => 'keaktifan',     'name' => 'Keaktifan', 'weight' => 0.15, 'type' => 'benefit'],
            ['code' => 'catatan_kasus', 'name' => 'Catatan Kasus / Pelanggaran', 'weight' => 0.25, 'type' => 'cost'],
            ['code' => 'administrasi',  'name' => 'Administrasi', 'weight' => 0.15, 'type' => 'benefit'],
            // ----------------------------------------------------------------------------
        ]);
    }
}