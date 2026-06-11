<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Form input / ubah nilai kriteria SMART untuk siswa tertentu
     */
    public function edit(Student $student)
    {
        // UBAH BARIS INI: Pastikan ejaannya 'assessment' (s-nya dobel di tengah)
        $student->load('assessment'); 
        
        return view('admin.students.assessment', compact('student'));
    }

    /**
     * Simpan / Perbarui nilai kriteria SMART siswa secara aman
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

        // Menggunakan updateOrCreate untuk mencegah error duplikasi data saat mengubah nilai lama
        Assesment::updateOrCreate(
            ['student_id' => $student->id], // Cari berdasarkan student_id
            [
                'absensi' => $validated['absensi'],
                'fisik_mental' => $validated['fisik_mental'],
                'keaktifan' => $validated['keaktifan'],
                'catatan_kasus' => $validated['catatan_kasus'],
                'administrasi' => $validated['administrasi'],
                'academic_year_id' => $activeYear ? $activeYear->id : $student->academic_year_id
            ]
        );

        return redirect()->route('admin.students.index')
                         ->with('success', 'Nilai parameter SMART berhasil disimpan untuk siswa ' . $student->name);
    }
}