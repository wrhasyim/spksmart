<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Tampilkan form input nilai untuk satu siswa spesifik
     */
    public function edit(Student $student)
    {
        // Ambil data nilai jika sudah ada, atau buat instance kosong
        $assessment = $student->assessment ?? new Assessment();
        
        return view('admin.assessments.edit', compact('student', 'assessment'));
    }

    /**
     * Simpan atau Update nilai dari Hubin
     */
    public function update(Request $request, Student $student)
    {
        // Validasi agar Hubin tidak salah ketik (harus angka 0-100)
        $validated = $request->validate([
            'absensi' => 'required|numeric|min:0|max:100',
            'fisik_mental' => 'required|numeric|min:0|max:100',
            'keaktifan' => 'required|numeric|min:0|max:100',
            'catatan_kasus' => 'required|numeric|min:0|max:100',
            'administrasi' => 'required|numeric|min:0|max:100',
        ]);

        // Gunakan updateOrCreate agar kode lebih pendek & rapi
        Assessment::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $student->academic_year_id,
            ],
            $validated
        );

        return redirect()->route('admin.students.index')
                         ->with('success', 'Data penilaian siswa ' . $student->name . ' berhasil disimpan!');
    }
}