<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Tampilkan daftar tahun ajaran
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        return view('admin.academic_years.index', compact('academicYears'));
    }

    /**
     * Simpan tahun ajaran baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name',
        ]);

        // Secara default, tahun ajaran baru tidak langsung aktif
        AcademicYear::create([
            'name' => $validated['name'],
            'is_active' => false,
        ]);

        // PERBAIKAN: Menggunakan garis bawah (underscore) sesuai routes/web.php
        return redirect()->route('admin.academic_years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Jadikan tahun ajaran terpilih sebagai periode aktif
     */
    public function setActive(AcademicYear $academic_year)
    {
        // Nonaktifkan semua tahun ajaran terlebih dahulu
        AcademicYear::query()->update(['is_active' => false]);

        // Aktifkan tahun ajaran yang dipilih
        $academic_year->update(['is_active' => true]);

        // PERBAIKAN: Menggunakan garis bawah (underscore) sesuai routes/web.php
        return redirect()->route('admin.academic_years.index')->with('success', "Tahun ajaran {$academic_year->name} berhasil diaktifkan sebagai periode saat ini.");
    }

    /**
     * Hapus tahun ajaran
     */
    public function destroy(AcademicYear $academic_year)
    {
        // Mencegah penghapusan periode yang sedang aktif untuk menjaga integritas data
        if ($academic_year->is_active) {
            return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }

        $academic_year->delete();
        
        // PERBAIKAN: Menggunakan garis bawah (underscore) sesuai routes/web.php
        return redirect()->route('admin.academic_years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}