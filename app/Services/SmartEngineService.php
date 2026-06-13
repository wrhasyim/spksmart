<?php

namespace App\Services;

use App\Models\Placement;
use App\Models\Student;
use App\Models\Criterion;
use App\Models\CompanySlot;
use Illuminate\Support\Facades\DB;

class SmartEngineService
{
    private $bounds = ['min' => 0, 'max' => 100];

    // Menghitung bobot normalisasi secara dinamis dari tabel kriteria
    private function getNormalizedWeights(): array
    {
        $criterias = Criterion::all();
        $totalWeight = $criterias->sum('weight');
        $totalWeight = $totalWeight > 0 ? $totalWeight : 1.0;

        $normalized = [];
        foreach ($criterias as $criterion) {
            // Gunakan ID kriteria sebagai key, agar cocok dengan JSON scores_data
            $normalized[$criterion->id] = $criterion->weight / $totalWeight;
        }
        return $normalized;
    }

    // Menghitung skor SMART murni membaca dari JSON
    public function calculateScore($assessment): float
    {
        $weights = $this->getNormalizedWeights();
        $criterias = Criterion::all(); 
        $score = 0;

        // Ambil data JSON array (pastikan di Model Assessment, scores_data sudah di-cast ke 'array')
        $scoresData = $assessment->scores_data ?? [];

        foreach ($criterias as $crit) {
            $id = $crit->id;
            $type = strtolower($crit->type);
            $weight = $weights[$id] ?? 0;
            
            // Ambil nilai dari JSON, jika belum ada set 0
            $val = $scoresData[$id] ?? 0;

            if ($type === 'benefit') {
                $utility = ($val - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
                $score += $utility * $weight;
            } elseif ($type === 'cost') {
                $utility = ($this->bounds['max'] - $val) / ($this->bounds['max'] - $this->bounds['min']);
                $score += $utility * $weight;
            }
        }
        return round($score * 100, 2);
    }

    // Proses Utama Pencocokan (Matchmaking)
    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            // 1. CLEANUP (Hapus history pencocokan sistem yang lama/gagal)
            Placement::where('academic_year_id', $academicYearId)
                     ->whereNull('company_id') 
                     ->delete();

            // Ambil siswa yang BELUM Lolos (Bypass siswa yang sudah di-lock manual)
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('status', '!=', 'lolos_prakerin')
                ->with(['assessment', 'major'])
                ->get();

            // Load Slot & Relasi Banyak Jurusan (Many to Many)
            $companySlots = CompanySlot::where('academic_year_id', $academicYearId)->with('majors')->get();
            foreach ($companySlots as $slot) {
                $used = Placement::where('company_slot_id', $slot->id)->count();
                $slot->available_quota = max(0, $slot->quota - $used);
            }

            // Hitung Skor
            foreach ($students as $student) {
                if (!$student->assessment) {
                    $student->final_score = 0;
                    continue; 
                }
                $student->final_score = $this->calculateScore($student->assessment);
            }

            // Urutkan siswa murni dari Skor Tertinggi
            $students = $students->sortByDesc('final_score');

            // 2. PROSES PENEMPATAN
            foreach ($students as $student) {
                foreach ($companySlots as $slot) {
                    if ($this->tryPlaceStudent($student, $slot, $academicYearId)) {
                        break; // Jika masuk, lanjut ke siswa berikutnya
                    }
                }
            }

            // Siswa yang sisa ditandai pembinaan
            Student::where('academic_year_id', $academicYearId)
                ->where('status', '!=', 'lolos_prakerin')
                ->whereDoesntHave('placement', function($q) {
                    $q->whereNotNull('company_id');
                })
                ->update(['status' => 'pembinaan']);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Syarat Lolos ke Perusahaan
    private function tryPlaceStudent($student, $companySlot, $academicYearId): bool
    {
        if ($companySlot->available_quota <= 0) return false;

        // CEK JURUSAN (Many to Many)
        // Mengecek apakah jurusan siswa ada di dalam daftar jurusan yang diterima slot ini
        if (!$companySlot->majors->contains('id', $student->major_id)) {
            return false;
        }

        // Eksekusi Penempatan
        Placement::create([
            'student_id'        => $student->id,
            'company_id'        => $companySlot->company_id, 
            'company_slot_id'   => $companySlot->id,
            'final_smart_score' => $student->final_score,
            'placement_method'  => 'SYSTEM',
            'academic_year_id'  => $academicYearId
        ]);

        $companySlot->available_quota--; 
        $student->update(['status' => 'lolos_prakerin']);

        return true;
    }
}