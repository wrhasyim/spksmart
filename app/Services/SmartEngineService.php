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

        // Ambil data JSON array
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

    // Mengambil nilai Absensi secara spesifik dari JSON
    // (Asumsi: Anda harus memastikan nama/kata 'absen' ada di Kriteria Absensi)
    private function getAbsensiScore($assessment): float
    {
        $scoresData = $assessment->scores_data ?? [];
        
        // Cari ID kriteria yang mengandung kata 'absen' atau 'kehadiran'
        $absensiCriterion = Criterion::where('name', 'like', '%absen%')
                                     ->orWhere('name', 'like', '%hadir%')
                                     ->first();

        if ($absensiCriterion) {
            return (float) ($scoresData[$absensiCriterion->id] ?? 0);
        }

        return 0; // Jika tidak ditemukan, default 0
    }

    // Proses Utama Pencocokan (Matchmaking)
    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            // 1. CLEANUP (Hapus history pencocokan sistem yang lama/gagal)
            // Hanya hapus yang by SYSTEM, biarkan MANUAL_OVERRIDE tetap ada
            Placement::where('academic_year_id', $academicYearId)
                     ->where('placement_method', 'SYSTEM')
                     ->delete();

            // Kembalikan status siswa yang sebelumnya lulus karena sistem, menjadi belum diproses
            Student::where('academic_year_id', $academicYearId)
                   ->whereDoesntHave('placement', function($query) {
                       // Siswa yang tidak punya penempatan manual
                       $query->where('placement_method', 'MANUAL_OVERRIDE');
                   })
                   ->update(['status' => 'belum_diproses']); // Reset status

            // Ambil siswa yang BELUM Lolos (Bypass siswa yang sudah di-lock manual)
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('status', '!=', 'lolos_prakerin')
                ->with(['assessment', 'major'])
                ->get();

            // Load Slot yang aktif pada periode tersebut
            $companySlots = CompanySlot::where('academic_year_id', $academicYearId)->get();
            
            // Hitung ketersediaan kuota aktual per slot
            foreach ($companySlots as $slot) {
                $used = Placement::where('company_slot_id', $slot->id)->count();
                $slot->available_quota = max(0, $slot->quota - $used);
            }

            // Hitung Skor Total SMART & Ekstrak Nilai Absensi
            foreach ($students as $student) {
                if (!$student->assessment) {
                    $student->final_score = 0;
                    $student->absensi_score = 0;
                    continue; 
                }
                
                $student->final_score = $this->calculateScore($student->assessment);
                $student->absensi_score = $this->getAbsensiScore($student->assessment);
            }

            // Urutkan siswa secara *descending* (Skor Tertinggi lebih dulu diutamakan)
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

    // Syarat Lolos ke Perusahaan (Logika Filtering yang Lebih Ketat)
    private function tryPlaceStudent($student, $companySlot, $academicYearId): bool
    {
        // 1. Cek Ketersediaan Kuota
        if ($companySlot->available_quota <= 0) return false;

        // 2. CEK JURUSAN (Sekarang One-to-Many di tabel company_slots)
        if ($companySlot->major_id !== $student->major_id) {
            return false;
        }

        // 3. CEK PERSYARATAN GENDER
        if ($companySlot->gender_requirement !== 'Semua') {
            if ($companySlot->gender_requirement !== $student->gender) {
                return false;
            }
        }

        // 4. CEK MINIMAL SKOR TOTAL SPK
        if ($student->final_score < $companySlot->min_total_score) {
            return false;
        }

        // 5. CEK MINIMAL SKOR ABSENSI
        if ($student->absensi_score < $companySlot->min_absensi_score) {
            return false;
        }

        // Jika semua filter lolos, Eksekusi Penempatan!
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