<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\AcademicYear;
use App\Models\CompanySlot;
use App\Models\Placement;
use App\Models\Major;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Tampilkan daftar perusahaan (Master Data)
     */
    public function index(Request $request)
    {
        $companies = Company::orderBy('name', 'asc')->paginate(10);
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Tampilkan Detail Perusahaan + Manajemen Gelombang Lowongan (Master-Detail)
     */
   public function show(Company $company, Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        $allYears = AcademicYear::all();
        $majors = Major::all();

        $slots = CompanySlot::where('company_id', $company->id)
            ->where('academic_year_id', $selectedYearId)
            ->with('majors')
            ->get()
            ->map(function ($slot) {
                // LOGIKA BARU: Hitung siswa HANYA berdasarkan company_slot_id ini
                $kuotaTerisi = Placement::where('company_slot_id', $slot->id)->count();

                $slot->kuota_terisi = $kuotaTerisi;
                $slot->sisa_kuota = max(0, $slot->quota - $kuotaTerisi);
                return $slot;
            });

        return view('admin.companies.show', compact('company', 'slots', 'allYears', 'selectedYearId', 'activeYear', 'majors'));
    }

    /**
     * Tampilkan form untuk menambah perusahaan baru
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Simpan data perusahaan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Perusahaan mitra berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengubah data perusahaan
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Perbarui data perusahaan yang ada di database
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Hapus data perusahaan dari database
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return redirect()->route('admin.companies.index')
                             ->with('success', 'Data perusahaan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Perusahaan tidak dapat dihapus karena masih memiliki riwayat gelombang lowongan.');
        }
    }
}