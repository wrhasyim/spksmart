<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Company;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;

class SmartEngineService
{
    // Konfigurasi Bobot Dasar SMART (Total = 100)
    private $rawWeights = [
        'absensi' => 30,
        'fisik_mental' => 15,
        'keaktifan' => 15,
        'catatan_kasus' => 25,
        'administrasi' => 15,
    ];

    private $bounds = [
        'min' => 0,
        'max' => 100
    ];

    private function getNormalizedWeights(): array
    {
        $totalWeight = array_sum($this->rawWeights);
        $normalized = [];
        foreach ($this->rawWeights as $key => $weight) {
            $normalized[$key] = $weight / $totalWeight;
        }
        return $normalized;
    }

    public function calculateScore($assessment): float
    {
        $weights = $this->getNormalizedWeights();
        $score = 0;

        // Benefit Criteria
        $benefitCriteria = ['absensi', 'fisik_mental', 'keaktifan', 'administrasi'];
        foreach ($benefitCriteria as $criteria) {
            $utility = ($assessment->$criteria - $this->bounds['min']) / ($this->bounds['max'] - $this->bounds['min']);
            $score += $utility * $weights[$criteria];
        }

        // Cost Criteria (Catatan Kasus)
        $utilityCost = ($this->bounds['max'] - $assessment->catatan_kasus) / ($this->bounds['max'] - $this->bounds['min']);
        $score += $utilityCost * $weights['catatan_kasus'];

        return round($score * 100, 2);
    }

    public function runMatchmaking($academicYearId)
    {
        DB::beginTransaction();
        try {
            Placement::where('academic_year_id', $academicYearId)->delete();

            $students = Student::where('academic_year_id', $academicYearId)
                ->with(['assessment', 'major'])
                ->get();

            $companies = Company::where('academic_year_id', $academicYearId)->get();

            $studentScores = [];
            foreach ($students as $student) {
                if (!$student->assessment) continue;
                $student->final_score = $this->calculateScore($student->assessment);
                $studentScores[] = $student;
            }

            usort($studentScores, fn($a, $b) => $b->final_score <=> $a->final_score);

            foreach ($studentScores as $student) {
                $placed = false;

                foreach ($companies as $company) {
                    if ($this->tryPlaceStudent($student, $company, $academicYearId)) {
                        $placed = true;
                        break;
                    }
                }

                if (!$placed) {
                    Placement::create([
                        'student_id' => $student->id,
                        'company_id' => null,
                        'final_smart_score' => $student->final_score,
                        'placement_method' => 'SYSTEM',
                        'notes' => 'Tidak memenuhi standar industri atau kuota penuh. Masuk program pembinaan.',
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

    private function tryPlaceStudent($student, $company, $academicYearId): bool
    {
        if ($company->quota <= 0) return false;

        if ($student->major_id !== $company->major_id) return false;
        if ($company->gender_requirement !== 'ALL' && $company->gender_requirement !== $student->gender) return false;

        if ($student->final_score < $company->min_total_score) return false;
        if ($student->assessment->absensi < $company->min_absensi_score) return false;

        Placement::create([
            'student_id' => $student->id,
            'company_id' => $company->id,
            'final_smart_score' => $student->final_score,
            'placement_method' => 'SYSTEM',
            'academic_year_id' => $academicYearId
        ]);

        $company->quota -= 1;
        $student->update(['status' => 'lolos_prakerin']);

        return true;
    }
}