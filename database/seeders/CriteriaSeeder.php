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
            ['code' => 'absensi', 'name' => 'Nilai Absensi', 'weight' => 0.30, 'type' => 'benefit'],
            ['code' => 'fisik', 'name' => 'Fisik & Mental', 'weight' => 0.15, 'type' => 'benefit'],
            ['code' => 'aktif', 'name' => 'Keaktifan', 'weight' => 0.15, 'type' => 'benefit'],
            ['code' => 'kasus', 'name' => 'Catatan Kasus / Pelanggaran', 'weight' => 0.25, 'type' => 'cost'],
            ['code' => 'admin', 'name' => 'Administrasi', 'weight' => 0.15, 'type' => 'benefit'],
        ]);
    }
}