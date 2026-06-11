<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Tampilkan daftar perusahaan beserta perhitungan sisa kuota (terikat tahun ajaran)
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        $allYears = AcademicYear::all();

        // Mengambil data perusahaan beserta jumlah siswa yang sudah ditempatkan (terisi)
        $companies = Company::with(['major', 'academicYear'])
            ->withCount(['placements' => function($query) use ($selectedYearId) {
                $query->where('academic_year_id', $selectedYearId)
                      ->whereNotNull('company_id');
            }])
            ->where('academic_year_id', $selectedYearId)
            ->get();

        return view('admin.companies.index', compact('companies', 'allYears', 'selectedYearId'));
    }

    /**
     * Tampilkan form untuk menambah perusahaan baru
     */
    public function create()
    {
        $majors = Major::all();
        $academicYears = AcademicYear::where('is_active', true)->get();
        return view('admin.companies.create', compact('majors', 'academicYears'));
    }

    /**
     * Simpan data perusahaan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'major_id' => 'required|exists:majors,id',
            'gender_requirement' => 'required|in:L,P,ALL',
            'min_total_score' => 'required|numeric|min:0|max:100',
            'min_absensi_score' => 'required|numeric|min:0|max:100',
            'min_fisik_score' => 'required|numeric|min:0|max:100',
            'min_keaktifan_score' => 'required|numeric|min:0|max:100',
            'min_administrasi_score' => 'required|numeric|min:0|max:100',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Perusahaan mitra berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengubah data perusahaan
     */
    public function edit(Company $company)
    {
        $majors = Major::all();
        $academicYears = AcademicYear::where('is_active', true)->get();
        return view('admin.companies.edit', compact('company', 'majors', 'academicYears'));
    }

    /**
     * Perbarui data perusahaan yang ada di database
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'major_id' => 'required|exists:majors,id',
            'gender_requirement' => 'required|in:L,P,ALL',
            'min_total_score' => 'required|numeric|min:0|max:100',
            'min_absensi_score' => 'required|numeric|min:0|max:100',
            'min_fisik_score' => 'required|numeric|min:0|max:100',
            'min_keaktifan_score' => 'required|numeric|min:0|max:100',
            'min_administrasi_score' => 'required|numeric|min:0|max:100',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Hapus data perusahaan dari database
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }
}