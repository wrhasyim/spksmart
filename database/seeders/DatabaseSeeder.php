<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil SpkInitialSeeder yang sudah kita buat sebelumnya
        $this->call([
            SpkInitialSeeder::class,
            CriteriaSeeder::class,
        ]);
    }
}