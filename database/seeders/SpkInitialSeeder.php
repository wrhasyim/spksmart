<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;
use App\Models\Major;
use App\Models\Company;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\User;

class SpkInitialSeeder extends Seeder
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

        // 3. Buat Perusahaan / Industri dengan Passing Grade & Kriteria Khusus
        Company::create([
            'name' => 'PT. Solusi Digital Asia',
            'address' => 'Jakarta Selatan',
            'quota' => 2,
            'major_id' => $rpl->id,
            'gender_requirement' => 'ALL',
            'min_total_score' => 75,       // Minimal skor SMART 75
            'min_absensi_score' => 80,     // Minimal nilai absensi 80
            'academic_year_id' => $academicYear->id
        ]);

        Company::create([
            'name' => 'PT. Jaringan Nusantara',
            'address' => 'Bekasi',
            'quota' => 1,
            'major_id' => $tkj->id,
            'gender_requirement' => 'L',   // Hanya menerima Laki-laki
            'min_total_score' => 70,
            'academic_year_id' => $academicYear->id
        ]);

        // 4. Buat Data Siswa Disertai Kelas
        $studentBudi = Student::create([
            'nisn' => '1234567891',
            'name' => 'Budi Santoso',
            'class' => 'XII RPL 1',
            'gender' => 'L',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id
        ]);

        $studentSiti = Student::create([
            'nisn' => '1234567892',
            'name' => 'Siti Nurhaliza',
            'class' => 'XII RPL 2',
            'gender' => 'P',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id
        ]);

        // 5. Input Nilai Siswa (Didapat dari Kesiswaan)
        Assessment::create([
            'student_id' => $studentBudi->id,
            'absensi' => 95,
            'fisik_mental' => 85,
            'keaktifan' => 90,
            'catatan_kasus' => 5, // Cost: Skala 0-100 (makin kecil makin baik)
            'administrasi' => 100,
            'academic_year_id' => $academicYear->id
        ]);

        Assessment::create([
            'student_id' => $studentSiti->id,
            'absensi' => 65,
            'fisik_mental' => 70,
            'keaktifan' => 60,
            'catatan_kasus' => 40, 
            'administrasi' => 50,
            'academic_year_id' => $academicYear->id
        ]);

        // 6. Buat Akun Hubin / Admin untuk Login
        User::create([
            'name' => 'Administrator Hubin',
            'username' => 'adminhubin',
            'password' => bcrypt('password'), // Password default: password
            'role' => 'hubin'
        ]);
    }
}