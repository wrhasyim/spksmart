<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;

class SmartEngineService
{
    // Konfigurasi Bobot Dasar (Dalam prakteknya bisa diambil dari Database)
    // Contoh total bobot = 100
    private $rawWeights = [
        'absensi' => 30,
        'fisik_mental' => 15,
        'keaktifan' => 15,
        'catatan_kasus' => 25,
        'administrasi' => 15,
    ];

    // Parameter Nilai Min & Max untuk setiap kriteria (Skala 0-100)
    private $bounds = [
        'min' => 0,
        'max' => 100
    ];

    /**
     * Normalisasi Bobot agar totalnya menjadi 1
     */
    private function getNormalizedWeights(): array
    {
        $totalWeight = array_sum($this->rawWeights);
        $normalized = [];
        foreach ($this->rawWeights as $key => $weight) {
            $normalized[$key] = $weight / $totalWeight;
        }
        return $normalized;
    }

    /**
     * Hitung Skor Akhir SMART untuk 1 Siswa
     */
    public function calculateScore($assessment): float
    {
        $weights = $this->getNormalizedWeights();
        $score = 0;

        // 1. Benefit Criteria (Makin tinggi makin baik)
        // Rumus: (C_out - C_min) / (C_max - C_min)
        $benefitCriteria = ['absensi', 'fisik_mental', 'keaktifan', 'administrasi'];
        foreach ($benefitCriteria as $criteria) {
            $utility = ($assessment->$criteria - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
            $score += $utility * $weights[$criteria];
        }

        // 2. Cost Criteria (Makin kecil makin baik - Catatan Kasus)
        // Rumus: (C_max - C_out) / (C_max - C_min)
        $utilityCost = ($this->bounds['max'] - $assessment->catatan_kasus) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityCost * $weights['catatan_kasus'];

        // Kembalikan dalam format persentase 0-100
        return round($score * 100, 2);
    }

    /**
     * Eksekusi Matchmaking (Penempatan Otomatis)
     */
    /**
     * Eksekusi Matchmaking (Penempatan Otomatis Murni Berdasarkan Nilai)
     */
    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            // 1. Reset penempatan sebelumnya di periode ini
            Placement::where('academic_year_id', $academicYearId)->delete();

            // 2. Ambil data Siswa beserta Nilai dan Jurusannya
            $students = User::where('role', 'siswa')
                ->where('academic_year_id', $academicYearId)
                ->with(['assessment', 'major'])
                ->get();

            // 3. Ambil data Perusahaan
            $companies = Company::where('academic_year_id', $academicYearId)->get();

            // 4. Hitung skor SMART semua siswa
            $studentScores = [];
            foreach ($students as $student) {
                if (!$student->assessment) continue; // Skip jika belum ada nilai
                $student->final_score = $this->calculateScore($student->assessment);
                $studentScores[] = $student;
            }

            // 5. Ranking: Urutkan siswa dari skor tertinggi ke terendah
            usort($studentScores, fn($a, $b) => $b->final_score <=> $a->final_score);

            // 6. Looping Penempatan (Otomatis berdasarkan kualifikasi & kuota)
            foreach ($studentScores as $student) {
                $placed = false;

                // Cek ke setiap perusahaan yang ada
                foreach ($companies as $company) {
                    if ($this->tryPlaceStudent($student, $company, $academicYearId)) {
                        $placed = true;
                        break; // Stop mencari jika sudah dapat tempat
                    }
                }

                // Jika nilainya di bawah standar semua perusahaan atau kuota penuh -> Pembinaan
                if (!$placed) {
                    Placement::create([
                        'user_id' => $student->id,
                        'company_id' => null, // Pembinaan
                        'final_smart_score' => $student->final_score,
                        'placement_method' => 'SYSTEM',
                        'notes' => 'Tidak memenuhi standar industri atau kuota penuh.',
                        'academic_year_id' => $academicYearId
                    ]);
                    
                    $student->update(['status' => 'pembinaan']);
                }
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Helper Fungsi untuk mengecek syarat & kuota
     */
    private function tryPlaceStudent($student, $company, $academicYearId): bool
    {
        // Cek Kuota
        if ($company->quota <= 0) return false;

        // Cek Hard Filter: Jurusan & Gender
        if ($student->major_id !== $company->major_id) return false;
        if ($company->gender_requirement !== 'ALL' && $company->gender_requirement !== $student->gender) return false;

        // Cek Passing Grade (Batas Minimum Perusahaan)
        if ($student->final_score < $company->min_total_score) return false;
        if ($student->assessment->absensi < $company->min_absensi_score) return false;
        // ... (Tambahkan pengecekan fisik, keaktifan, dll jika diperlukan)

        // Jika Lolos semua syarat, Lakukan Penempatan
        Placement::create([
            'user_id' => $student->id,
            'company_id' => $company->id,
            'final_smart_score' => $student->final_score,
            'placement_method' => 'SYSTEM',
            'academic_year_id' => $academicYearId
        ]);

        // Kurangi kuota perusahaan sementara di memory (agar iterasi selanjutnya akurat)
        $company->quota -= 1;

        // Update status siswa
        $student->update(['status' => 'lolos_prakerin']);

        return true;
    }
}