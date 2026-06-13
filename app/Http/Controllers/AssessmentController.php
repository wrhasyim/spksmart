<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\AcademicYear;
use App\Models\Criterion;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Form input / ubah nilai kriteria SMART untuk siswa tertentu
     */
    public function edit(Student $student)
    {
        $student->load('assessment'); 
        
        // Ambil semua kriteria yang ada di Master Data
        $criteria = Criterion::orderBy('id', 'asc')->get(); 
        
        return view('admin.students.assessment', compact('student', 'criteria'));
    }

    /**
     * Simpan / Perbarui nilai kriteria SMART siswa secara aman ke dalam JSON
     */
    public function update(Request $request, Student $student)
    {
        // Validasi input array dinamis
        $validated = $request->validate([
            'scores' => 'required|array', // Harus berupa array (JSON)
            'scores.*' => 'required|numeric|min:0|max:100', // Nilai tiap kriteria 0-100
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Menyimpan nilai dinamis ke kolom JSON 'scores_data'
        Assessment::updateOrCreate(
            ['student_id' => $student->id],
            [
                'scores_data' => $validated['scores'], 
                'academic_year_id' => $activeYear ? $activeYear->id : $student->academic_year_id
            ]
        );

        return redirect()->route('admin.students.index')
                         ->with('success', 'Nilai parameter SMART berhasil disimpan untuk siswa ' . $student->name);
    }
}