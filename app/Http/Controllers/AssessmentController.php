<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Form input nilai kriteria SMART untuk siswa tertentu
     */
    public function edit(Student $student)
    {
        $student->load('assessment');
        return view('admin.students.assessment', compact('student'));
    }

    /**
     * Simpan / Perbarui nilai kriteria SMART siswa
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'absensi' => 'required|numeric|min:0|max:100',
            'fisik_mental' => 'required|numeric|min:0|max:100',
            'keaktifan' => 'required|numeric|min:0|max:100',
            'catatan_kasus' => 'required|numeric|min:0|max:100',
            'administrasi' => 'required|numeric|min:0|max:100',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        Assessment::updateOrCreate(
            ['student_id' => $student->id],
            array_merge($validated, [
                'academic_year_id' => $activeYear ? $activeYear->id : null
            ])
        );

        return redirect()->route('admin.students.index')->with('success', 'Nilai kriteria SMART berhasil disimpan untuk siswa ' . $student->name);
    }
}