<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin Hubin (Menggunakan Username, bukan Email)
        User::updateOrCreate(
            ['username' => 'adminhubin'],
            [
                'name' => 'Kepala Hubin SMK',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Initial Pengaturan Aplikasi & Kop Surat
        DB::table('app_settings')->updateOrInsert(
            ['id' => 1],
            [
                'instansi_atas' => 'PEMERINTAH PROVINSI JAWA BARAT',
                'nama_sekolah' => 'SMK Negeri 1 SPK',
                'alamat_sekolah' => 'Jl. Pendidikan No.1, Kota Cerdas',
                'kontak_sekolah' => 'Telp: 021-12345 | Web: smkn1-spk.sch.id',
                'nama_kepala_sekolah' => 'Drs. H. Pendidik Utama, M.Pd.',
                'nip_kepala_sekolah' => '19800101 200501 1 001',
                'teks_pengantar_surat' => 'Dengan hormat, bersama surat ini kami dari pihak sekolah mengajukan permohonan penempatan siswa/i kami untuk melaksanakan Praktik Kerja Lapangan (PKL) di perusahaan yang Bapak/Ibu pimpin.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}