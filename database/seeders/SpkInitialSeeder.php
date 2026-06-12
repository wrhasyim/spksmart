<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Major;
use App\Models\Company;
use App\Models\CompanySlot;
use App\Models\Student;
use App\Models\Assessment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SpkInitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED DATA USER (ADMIN / HUBIN)
        User::create([
            'name' => 'Administrator Hubin',
            'username' => 'adminhubin', // Menggunakan username
            'password' => Hash::make('password'), // Password default: password
        ]);

        // 2. SEED DATA TAHUN AJARAN
        $academicYear = AcademicYear::create([
            'name' => '2025/2026 Ganjil', // Nama disatukan karena tidak ada kolom semester
            'is_active' => true,
        ]);

        // 3. SEED DATA JURUSAN (MAJORS)
        $rpl = Major::create([
            'name' => 'Rekayasa Perangkat Lunak',
            'code' => 'RPL',
        ]);

        $tkj = Major::create([
            'name' => 'Teknik Komputer dan Jaringan',
            'code' => 'TKJ',
        ]);

        // 4. SEED DATA KRITERIA (Dilewati / Hardcoded di Engine)
        // Kriteria saat ini masih menggunakan sistem hardcode di SmartEngineService
        // sehingga tabel/model Criteria tidak perlu di-seed.


        // 5. SEED DATA INDUK PERUSAHAAN (Tabel Master Profil - companies)
        $pt1 = Company::create([
            'name' => 'PT. Solusi Digital Asia',
            'address' => 'Jl. Jendral Sudirman No. 45, Jakarta Selatan',
            'phone' => '021-5551234',
            'email' => 'hrd@solusidigital.co.id',
        ]);

        $pt2 = Company::create([
            'name' => 'PT. Nusantara Infra Network',
            'address' => 'Kawasan Industri Timur Blok C3, Bekasi',
            'phone' => '021-8889876',
            'email' => 'career@nusantarainfra.net',
        ]);

        $pt3 = Company::create([
            'name' => 'CV. Media Kreatif Utama',
            'address' => 'Jl. Merdeka No. 12, Bandung',
            'phone' => '022-4443322',
            'email' => 'studio@mediakreatif.com',
        ]);


        // 6. SEED DATA SLOT LOWONGAN / GELOMBANG PERUSAHAAN (Tabel Transaksi - company_slots)
        
        // PT 1 membuka slot untuk siswa RPL durasi 3 bulan
        $startPt1 = Carbon::create(2026, 7, 1); 
        CompanySlot::create([
            'company_id' => $pt1->id,
            'academic_year_id' => $academicYear->id,
            'major_id' => $rpl->id,
            'batch_name' => 'Utama - Batch A',
            'quota' => 2,
            'min_total_score' => 75.00,
            'min_absensi_score' => 80,
            'start_date' => $startPt1->toDateString(),
            'end_date' => $startPt1->copy()->addMonthsNoOverflow(3)->toDateString(), 
        ]);

        // PT 2 membuka slot khusus anak TKJ durasi 6 bulan
        $startPt2 = Carbon::create(2026, 8, 1); 
        CompanySlot::create([
            'company_id' => $pt2->id,
            'academic_year_id' => $academicYear->id,
            'major_id' => $tkj->id,
            'batch_name' => 'Reguler',
            'quota' => 3,
            'min_total_score' => 70.00,
            'min_absensi_score' => 75,
            'start_date' => $startPt2->toDateString(),
            'end_date' => $startPt2->copy()->addMonthsNoOverflow(6)->toDateString(), 
        ]);

        // PT 1 meminta tambahan kiriman (GELOMBANG SUSULAN)
        $startPt1Susulan = Carbon::create(2026, 9, 1);
        CompanySlot::create([
            'company_id' => $pt1->id, 
            'academic_year_id' => $academicYear->id,
            'major_id' => $rpl->id,
            'batch_name' => 'Susulan - Batch B',
            'quota' => 1,
            'min_total_score' => 80.00, 
            'min_absensi_score' => 85,
            'start_date' => $startPt1Susulan->toDateString(),
            'end_date' => $startPt1Susulan->copy()->addMonthsNoOverflow(3)->toDateString(), 
        ]);


        // 7. SEED DATA SISWA & DATA NILAI KRITERIA (ASSESSMENT)

        // Siswa 1: Siswa Berprestasi Bagus (RPL)
        // Siswa 1: Siswa Berprestasi Bagus (RPL)
        $s1 = Student::create([
            'nisn' => '0012345671',
            'name' => 'Achmad Dhani',
            'class' => 'XII RPL 1',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id,
        ]);
        Assessment::create([
            'student_id' => $s1->id,
            'academic_year_id' => $academicYear->id,
            'absensi' => 98,   
            'fisik_mental' => 90,       
            'keaktifan' => 95,  
            'catatan_kasus' => 0, // Cost: Semakin kecil (0 kasus) semakin bagus utilitasnya      
            'administrasi' => 85,    
        ]);

        // Siswa 2: Siswa Standar / Menengah (RPL)
        $s2 = Student::create([
            'nisn' => '0012345672',
            'name' => 'Budi Santoso',
            'class' => 'XII RPL 1',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id,
        ]);
        Assessment::create([
            'student_id' => $s2->id,
            'academic_year_id' => $academicYear->id,
            'absensi' => 85,
            'fisik_mental' => 78,
            'keaktifan' => 80,
            'catatan_kasus' => 2,      
            'administrasi' => 70,
        ]);

        // Siswa 3 & Siswa 4: Kasus Nilai Identik/Kembar untuk menguji Tie-Breaker (RPL)
        $s3 = Student::create([
            'nisn' => '0012345673',
            'name' => 'Citra Lestari',
            'class' => 'XII RPL 2',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id,
        ]);
        Assessment::create([
            'student_id' => $s3->id,
            'academic_year_id' => $academicYear->id,
            'absensi' => 90,   
            'fisik_mental' => 85,
            'keaktifan' => 88,
            'catatan_kasus' => 0,
            'administrasi' => 75,
        ]);

        $s4 = Student::create([
            'nisn' => '0012345674',
            'name' => 'Dinda Kirana',
            'class' => 'XII RPL 2',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id,
        ]);
        Assessment::create([
            'student_id' => $s4->id,
            'academic_year_id' => $academicYear->id,
            'absensi' => 90, // Sengaja dibuat kembar persis dengan Siswa 3  
            'fisik_mental' => 85,
            'keaktifan' => 88,
            'catatan_kasus' => 0,
            'administrasi' => 75,
        ]);

        // Siswa 5: Siswa Bermasalah / Remedial (RPL)
        $s5 = Student::create([
            'nisn' => '0012345675',
            'name' => 'Eko Prasetyo',
            'class' => 'XII RPL 2',
            'major_id' => $rpl->id,
            'academic_year_id' => $academicYear->id,
        ]);
        Assessment::create([
            'student_id' => $s5->id,
            'academic_year_id' => $academicYear->id,
            'absensi' => 60,   
            'fisik_mental' => 65,
            'keaktifan' => 70,
            'catatan_kasus' => 15, // Cost: Nilai kasus tinggi sangat merugikan nilai akhir
            'administrasi' => 50,
        ]);
    }
}