<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Major;
use App\Models\Company;
use App\Models\User;
use App\Models\Assessment;

class SpkDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Tahun Ajaran Aktif
        $academicYear = AcademicYear::create([
            'name' => '2026/2027 - Ganjil',
            'is_active' => true
        ]);

        // 2. Buat Jurusan
        $rpl = Major::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak']);
        $tkj = Major::create(['code' => 'TKJ', 'name' => 'Teknik Komputer & Jaringan']);

        // 3. Buat Data Perusahaan dengan Passing Grade
        $companyIT = Company::create([
            'name' => 'PT. Teknologi Maju',
            'quota' => 2,
            'major_id' => $rpl->id,
            'gender_requirement' => 'ALL',
            'min_total_score' => 80, // Butuh nilai SMART total minimal 80
            'academic_year_id' => $academicYear->id
        ]);

        $companyNet = Company::create([
            'name' => 'PT. Jaringan Nusantara',
            'quota' => 1,
            'major_id' => $tkj->id,
            'gender_requirement' => 'L', // Hanya Laki-laki
            'min_total_score' => 75,
            'academic_year_id' => $academicYear->id
        ]);

        // 4. Buat Siswa beserta Nilainya
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@siswa.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'gender' => 'L',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id
        ]);

        Assessment::create([
            'user_id' => $budi->id,
            'absensi' => 95,
            'fisik_mental' => 80,
            'keaktifan' => 90,
            'catatan_kasus' => 0, // Bagus, tidak ada kasus
            'administrasi' => 100,
            'academic_year_id' => $academicYear->id
        ]);

        // Catatan: Budi pasti lolos ke PT. Teknologi Maju karena nilainya sangat tinggi
    }
}