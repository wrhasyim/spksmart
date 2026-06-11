<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['major', 'academicYear', 'assessment'])->get();
        return view('admin.students.index', compact('students'));
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
            'class' => 'required|string|max:100', // Validasi input kelas
            'gender' => 'required|in:L,P',
            'major_id' => 'required|exists:majors,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        Student::create(array_merge($validated, ['status' => 'pending']));

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}