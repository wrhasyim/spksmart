<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Criterion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SampleStudentsExport;
use App\Imports\StudentsImport;
use Exception;

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
    // FITUR IMPORT EXCEL DINAMIS
    // ==========================================
    
    public function downloadSample()
    {
        // Pastikan ada kriteria aktif sebelum mengunduh agar template kolomnya tidak kosong
        if (Criterion::count() == 0) {
            return back()->with('error', 'Gagal! Harap isi Master Data Kriteria terlebih dahulu sebelum mengunduh template Excel.');
        }

        return Excel::download(new SampleStudentsExport, 'Template_Import_Siswa_SPK.xlsx');
    }

    public function import(Request $request)
    {
        // Validasi file yang diunggah harus berformat excel dan ukuran tidak lebih dari 5MB
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120', 
        ]);

        try {
            // Jalankan class Import
            Excel::import(new StudentsImport, $request->file('file_excel'));
            return back()->with('success', 'Data Siswa beserta nilainya berhasil diimpor dengan sempurna!');
        } catch (Exception $e) {
            // Tangkap dan tampilkan pesan error jika ada ketidaksesuaian data (misal kode jurusan salah)
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}