<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\CompanySlot;
use App\Models\Placement;
use App\Models\Student;
use App\Models\Criterion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SmartEngineService
{
    private $bounds = [
        'min' => 0,
        'max' => 100
    ];

    /**
     * Menghitung nilai bobot yang sudah dinormalisasi menjadi pecahan desimal
     * secara dinamis dari tabel criterias.
     */
    private function getNormalizedWeights(): array
    {
        $criterias = Criterion::all();
        $totalWeight = $criterias->sum('weight');
        
        // Pencegahan pembagian dengan nol apabila data belum diatur
        $totalWeight = $totalWeight > 0 ? $totalWeight : 1.0;

        $normalized = [];
        foreach ($criterias as $criterion) {
            $normalized[$criterion->code] = $criterion->weight / $totalWeight;
        }
        
        return $normalized;
    }

    /**
     * Menghitung Skor Akhir SMART dari satu Assessment.
     */
    public function calculateScore($assessment): float
    {
        $weights = $this->getNormalizedWeights();
        $score = 0;

        // 1. Kriteria Benefit (Menggunakan bobot dinamis dari tabel criterias)
        // Absensi
        $utilityAbsensi = ($assessment->absensi - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityAbsensi * ($weights['absensi'] ?? 0);

        // Fisik & Mental
        $utilityFisik = ($assessment->fisik_mental - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityFisik * ($weights['fisik'] ?? 0);

        // Keaktifan
        $utilityAktif = ($assessment->keaktifan - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityAktif * ($weights['aktif'] ?? 0);

        // Administrasi
        $utilityAdmin = ($assessment->administrasi - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityAdmin * ($weights['admin'] ?? 0);

        // 2. Kriteria Cost (Catatan Kasus)
        // Rumus Cost: (C_max - C_out) / (C_max - C_min)
        $utilityCost = ($this->bounds['max'] - $assessment->catatan_kasus) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityCost * ($weights['kasus'] ?? 0);

        // Kembalikan nilai dalam bentuk puluhan (0-100)
        return round($score * 100, 2);
    }

    /**
     * Fungsi Inti Skripsi: Mencocokkan Siswa dengan Gelombang Perusahaan (Company Slot).
     */
    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            // 1. Bersihkan riwayat penempatan (Placement) di tahun ajaran aktif ini
            Placement::where('academic_year_id', $academicYearId)->delete();

            // 2. Reset Status Semua Siswa
            Student::where('academic_year_id', $academicYearId)->update(['status' => 'belum_prakerin']);

            // 3. Ambil data Siswa (yang sudah ada nilainya) & Data Gelombang Lowongan
            $students = Student::where('academic_year_id', $academicYearId)
                ->with(['assessment', 'major'])
                ->get();

            $companySlots = CompanySlot::where('academic_year_id', $academicYearId)->get();

            $studentScores = [];
            
            // 4. Hitung Skor SMART Setiap Siswa
            foreach ($students as $student) {
                if (!$student->assessment) continue; // Skip jika belum dinilai
                
                $student->final_score = $this->calculateScore($student->assessment);
                $studentScores[] = $student;
            }

            // 5. Urutkan Siswa Berdasarkan Skor Tertinggi (Ranking 1 ke bawah)
            usort($studentScores, fn($a, $b) => $b->final_score <=> $a->final_score);

            // 6. Proses Penempatan (Matchmaking)
            foreach ($studentScores as $student) {
                $placed = false;

                // Cari slot perusahaan yang cocok untuk siswa ini
                foreach ($companySlots as $slot) {
                    if ($this->tryPlaceStudent($student, $slot, $academicYearId)) {
                        $placed = true;
                        break; // Stop mencari perusahaan lain jika sudah dapat tempat
                    }
                }

                // Jika siswa tidak diterima di perusahaan manapun (Karena nilai kurang atau kuota habis)
                if (!$placed) {
                    Placement::create([
                        'student_id'        => $student->id,
                        'company_id'        => null, // Null berarti tidak dapat perusahaan
                        'final_smart_score' => $student->final_score,
                        'placement_method'  => 'SYSTEM',
                        'notes'             => 'Tidak memenuhi passing grade industri atau kuota penuh. Masuk antrean/pembinaan.',
                        'academic_year_id'  => $academicYearId
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
     * Mengecek dan menempatkan siswa jika memenuhi syarat Gelombang Lowongan.
     */
    private function tryPlaceStudent($student, $companySlot, $academicYearId): bool
    {
        // 1. Apakah Kuota Masih Ada?
        if ($companySlot->quota <= 0) return false;

        // 2. Apakah Jurusannya Sesuai?
        if ($student->major_id !== $companySlot->major_id) return false;

        // 3. Apakah Lulus Passing Grade Total SMART?
        if ($student->final_score < $companySlot->min_total_score) return false;

        // 4. Apakah Lulus Syarat Mutlak Absensi?
        if ($student->assessment->absensi < $companySlot->min_absensi_score) return false;

        // JIKA SEMUA SYARAT TERPENUHI -> Lakukan Penempatan ke tabel Placements
        Placement::create([
            'student_id'        => $student->id,
            'company_id'        => $companySlot->company_id, // KITA MENGAMBIL ID PERUSAHAAN DARI SLOT
            'final_smart_score' => $student->final_score,
            'placement_method'  => 'SYSTEM',
            'academic_year_id'  => $academicYearId
        ]);

        // Kurangi Kuota Perusahaan & Update Status Siswa
        $companySlot->quota -= 1;
        $student->update(['status' => 'lolos_prakerin']);

        return true;
    }
}