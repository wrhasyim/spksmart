<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        // Ambil data siswa beserta relasi jurusan dan tahun ajaran (Eager Loading agar cepat)
        $students = Student::with(['major', 'academicYear'])->latest()->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $majors = Major::all();
        $academicYears = AcademicYear::all();
        
        // Peringatan jika master data belum lengkap
        if ($majors->isEmpty() || $academicYears->isEmpty()) {
            return redirect()->route('admin.students.index')->with('error', 'Harap isi Master Jurusan dan Tahun Ajaran terlebih dahulu sebelum menambah siswa.');
        }

        return view('admin.students.create', compact('majors', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:50',
            'major_id' => 'required|exists:majors,id',
            'gender' => 'required|in:L,P',
            'parent_phone' => 'nullable|string|max:20',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil ditambahkan secara manual.');
    }

    public function edit(Student $student)
    {
        $majors = Major::all();
        $academicYears = AcademicYear::all();
        return view('admin.students.edit', compact('student', 'majors', 'academicYears'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'name' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:50',
            'major_id' => 'required|exists:majors,id',
            'gender' => 'required|in:L,P',
            'parent_phone' => 'nullable|string|max:20',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $student->update($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        // Soft delete siswa
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil dihapus (Soft Delete).');
    }

    // ==========================================
    // KERANGKA FITUR IMPORT EXCEL DINAMIS
    // ==========================================
    
    public function downloadSample()
    {
        // Nanti kita isi dengan logika pembuatan Template Dinamis
        return back()->with('info', 'Fitur Download Template Excel akan segera diaktifkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120', // Maks 5MB
        ]);

        // Nanti kita isi dengan logika Import Laravel Excel
        return back()->with('info', 'Fitur Import Excel akan segera diaktifkan.');
    }
}