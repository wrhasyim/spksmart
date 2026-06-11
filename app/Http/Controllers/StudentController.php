<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        $allYears = AcademicYear::all();

        // Ambil data siswa sesuai dengan tahun ajaran yang sedang dipilih/ditinjau
        $students = Student::with(['major', 'academicYear', 'assessment'])
            ->where('academic_year_id', $selectedYearId)
            ->get();

        return view('admin.students.index', compact('students', 'allYears', 'selectedYearId'));
    }

    public function create()
    {
        $majors = Major::all();
        $academicYears = AcademicYear::where('is_active', true)->get();
        return view('admin.students.create', compact('majors', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:100',
            'gender' => 'required|in:L,P',
            'major_id' => 'required|exists:majors,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        // Menggunakan status 'belum_prakerin' agar sesuai dengan ENUM database
        Student::create(array_merge($validated, ['status' => 'belum_prakerin']));

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}