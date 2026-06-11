<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Company;
use App\Models\Assessment;
use App\Models\Major;
use App\Models\AcademicYear;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil atau buat Tahun Ajaran aktif
        $academicYear = AcademicYear::firstOrCreate(
            ['is_active' => true],
            ['name' => '2026/2027 - Ganjil']
        );

        // 2. Ambil atau buat data Jurusan
        $majorRPL = Major::firstOrCreate(['code' => 'RPL'], ['name' => 'Rekayasa Perangkat Lunak']);
        $majorTKJ = Major::firstOrCreate(['code' => 'TKJ'], ['name' => 'Teknik Komputer dan Jaringan']);
        $majors = [$majorRPL->id, $majorTKJ->id];

        // 3. Buat 15 Perusahaan Mitra Dummy
        for ($i = 0; $i < 15; $i++) {
            Company::create([
                'name' => $faker->company,
                'address' => $faker->address,
                'quota' => $faker->numberBetween(1, 4), // Kuota 1 sampai 4 siswa
                'major_id' => $faker->randomElement($majors),
                'gender_requirement' => $faker->randomElement(['L', 'P', 'ALL', 'ALL', 'ALL']),
                'min_total_score' => $faker->randomFloat(2, 75, 85),
                'min_absensi_score' => $faker->numberBetween(70, 80),
                'min_fisik_score' => $faker->numberBetween(70, 80),
                'min_keaktifan_score' => $faker->numberBetween(70, 80),
                'min_administrasi_score' => $faker->numberBetween(70, 80),
                'academic_year_id' => $academicYear->id,
            ]);
        }

        // 4. Buat 40 Siswa beserta Nilai Kriteria (Assessment)
        for ($i = 0; $i < 40; $i++) {
            $gender = $faker->randomElement(['L', 'P']);
            $majorId = $faker->randomElement($majors);
            
            // Logika kelas sesuai jurusan
            $kelas = $majorId == $majorRPL->id 
                ? 'XII RPL ' . $faker->numberBetween(1, 3) 
                : 'XII TKJ ' . $faker->numberBetween(1, 3);

            // Membuat data siswa secara bersih TANPA kolom status
            $student = Student::create([
                'nisn' => $faker->unique()->numerify('##########'), // 10 digit angka unik
                'name' => $gender == 'L' ? $faker->firstNameMale . ' ' . $faker->lastName : $faker->firstNameFemale . ' ' . $faker->lastName,
                'class' => $kelas,
                'gender' => $gender,
                'major_id' => $majorId,
                'academic_year_id' => $academicYear->id,
            ]);

            // Input Nilai SMART Dummy yang realistis
            Assessment::create([
                'student_id' => $student->id,
                'absensi' => $faker->numberBetween(75, 100),
                'fisik_mental' => $faker->numberBetween(75, 100),
                'keaktifan' => $faker->numberBetween(70, 100),
                'administrasi' => $faker->numberBetween(80, 100),
                'catatan_kasus' => $faker->numberBetween(0, 20), // Kriteria Cost
                'academic_year_id' => $academicYear->id,
            ]);
        }
    }
}