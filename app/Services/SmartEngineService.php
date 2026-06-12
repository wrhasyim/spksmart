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

    private function getNormalizedWeights(): array
    {
        $criterias = Criterion::all();
        $totalWeight = $criterias->sum('weight');
        $totalWeight = $totalWeight > 0 ? $totalWeight : 1.0;

        $normalized = [];
        foreach ($criterias as $criterion) {
            $normalized[$criterion->code] = $criterion->weight / $totalWeight;
        }
        return $normalized;
    }

    public function calculateScore($assessment): float
    {
        $weights = $this->getNormalizedWeights();
        $criterias = Criterion::all(); 
        $score = 0;

        foreach ($criterias as $crit) {
            $code = $crit->code;
            $type = strtolower($crit->type);
            $weight = $weights[$code] ?? 0;
            $val = $assessment->$code ?? 0;

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

    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            // --- 1. LOCKING LOGIC & CLEANUP ---
            // Hanya hapus hasil penempatan yang gagal/pembinaan agar riwayat berhasil tetap tersimpan
            Placement::where('academic_year_id', $academicYearId)
                     ->whereNull('company_id') 
                     ->delete();

            // Ambil siswa yang BELUM Lolos Prakerin (Locking: yang sudah lolos diabaikan)
            $students = Student::where('academic_year_id', $academicYearId)
                ->where('status', '!=', 'lolos_prakerin')
                ->with(['assessment', 'major'])
                ->get();

            // Ambil semua slot dan hitung sisa kuota sebenarnya
            $companySlots = CompanySlot::where('academic_year_id', $academicYearId)->get();
            foreach ($companySlots as $slot) {
                // Hitung kuota yang sudah terpakai oleh siswa yang sudah 'lolos_prakerin'
                $used = Placement::where('company_slot_id', $slot->id)->count();
                $slot->available_quota = max(0, $slot->quota - $used);
            }

            // Hitung Skor
            foreach ($students as $student) {
                if (!$student->assessment) continue; 
                $student->final_score = $this->calculateScore($student->assessment);
            }

            // Urutkan: Skor Tertinggi -> Absensi Tertinggi
            $students = $students->sortByDesc(function($student) {
                return [$student->final_score, $student->assessment->absensi ?? 0];
            });

            // --- 2. PROSES PENEMPATAN ---
            foreach ($students as $student) {
                foreach ($companySlots as $slot) {
                    if ($this->tryPlaceStudent($student, $slot, $academicYearId)) {
                        break; 
                    }
                }
            }

            // Siswa sisa yang tidak masuk slot mana pun, tandai pembinaan
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

    private function tryPlaceStudent($student, $companySlot, $academicYearId): bool
    {
        // Gunakan available_quota yang sudah dihitung di runMatchmaking
        if ($companySlot->available_quota <= 0) return false;

        // Cek Jurusan
        if ($student->major_id !== $companySlot->major_id) return false;

        // --- 3. FILTER GENDER KETAT ---
        if (in_array($companySlot->gender_requirement, ['L', 'P'])) {
            $sGender = strtoupper(trim($student->gender ?? ''));
            $req = strtoupper(trim($companySlot->gender_requirement));

            // Normalisasi
            if (in_array($sGender, ['LAKI-LAKI', 'L', 'COWO'])) $sGender = 'L';
            if (in_array($sGender, ['PEREMPUAN', 'P', 'CEWE'])) $sGender = 'P';

            if ($sGender !== $req) return false; 
        }

        // Cek Passing Grade
        if ($student->final_score < $companySlot->min_total_score) return false;
        if ($student->assessment->absensi < $companySlot->min_absensi_score) return false;

        // --- 4. EKSEKUSI ---
        Placement::create([
            'student_id'        => $student->id,
            'company_id'        => $companySlot->company_id, 
            'company_slot_id'   => $companySlot->id,
            'final_smart_score' => $student->final_score,
            'placement_method'  => 'SYSTEM',
            'academic_year_id'  => $academicYearId
        ]);

        $companySlot->available_quota--; // Kurangi kuota sementara di memori
        $student->update(['status' => 'lolos_prakerin']);

        return true;
    }
}