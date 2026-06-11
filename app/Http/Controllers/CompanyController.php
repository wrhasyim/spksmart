<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Tampilkan daftar perusahaan mitra
     */
    public function index()
    {
        $companies = Company::with(['major', 'academicYear'])->get();
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Form tambah perusahaan
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
     * Hapus perusahaan
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Data perusahaan berhasil dihapus.');
    }
}