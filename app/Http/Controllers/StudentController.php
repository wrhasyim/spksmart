<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan pagination 10 data per halaman (terikat tahun ajaran)
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        $allYears = AcademicYear::all();

        // Mengambil data siswa menggunakan paginate(10) dan mempertahankan query string filter
        $students = Student::with(['major', 'academicYear', 'assessment'])
            ->where('academic_year_id', $selectedYearId)
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'allYears', 'selectedYearId'));
    }

    /**
     * Tampilkan form tambah siswa baru
     */
    public function create()
    {
        $majors = Major::all();
        $academicYears = AcademicYear::where('is_active', true)->get();
        return view('admin.students.create', compact('majors', 'academicYears'));
    }

    /**
     * Simpan data siswa baru ke database
     */
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

        Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Hapus data siswa dari database
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}