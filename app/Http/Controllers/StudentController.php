<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Major;
use App\Imports\StudentsImport;
use App\Exports\SampleStudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Tampilkan halaman utama daftar siswa
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        $allYears = AcademicYear::all();

        $students = Student::where('academic_year_id', $selectedYearId)
            ->with(['major', 'assessment'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'allYears', 'selectedYearId', 'activeYear'));
    }

    /**
     * Form tambah siswa manual
     */
    public function create()
    {
        $majors = Major::all();
        $academicYears = AcademicYear::all();
        return view('admin.students.create', compact('majors', 'academicYears'));
    }

    /**
     * Simpan tambah siswa manual
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|unique:students,nisn',
            'name' => 'required|string|max:255',
            'class' => 'required|string',
            'gender' => 'required|in:L,P',
            'major_id' => 'required|exists:majors,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $validated['status'] = 'belum_prakerin';

        Student::create($validated);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan secara manual.');
    }

    /**
     * Hapus data siswa
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * FITUR: Import Data Siswa dan Nilai dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file_excel'));
            return redirect()->route('admin.students.index')->with('success', 'Data Siswa dan Nilai berhasil diimpor ke sistem!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data. Pastikan format kolom sesuai. Error: ' . $e->getMessage());
        }
    }

    /**
     * FITUR: Download Contoh File Excel Template (.xlsx)
     */
    public function downloadSample()
    {
        return Excel::download(new SampleStudentsExport, 'template_sample_import_siswa.xlsx');
    }
}