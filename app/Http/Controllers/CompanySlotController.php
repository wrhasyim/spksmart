<?php

namespace App\Http\Controllers;

use App\Models\CompanySlot;
use App\Models\Company;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CompanySlotController extends Controller
{
    /**
     * Tampilkan form untuk menambah gelombang baru
     */
    public function create(Request $request)
    {
        $companyId = $request->get('company_id');
        if (!$companyId) {
            return redirect()->route('admin.companies.index')->with('error', 'Pilih perusahaan terlebih dahulu.');
        }

        $company = Company::findOrFail($companyId);
        $majors = Major::all();
        $academicYears = AcademicYear::where('is_active', true)->get();

        return view('admin.company_slots.create', compact('company', 'majors', 'academicYears'));
    }

    /**
     * Simpan gelombang baru dan KEMBALI ke halaman Detail Perusahaan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'         => 'required|exists:companies,id', // Hanya di fungsi store
            'academic_year_id'   => 'required|exists:academic_years,id',
            'major_id'           => 'required|exists:majors,id',
            'batch_name'         => 'required|string|max:255',
            'gender_requirement' => 'required|in:L,P,Semua', // <--- Tambahkan baris ini
            'quota'              => 'required|integer|min:1',
            'min_total_score'    => 'required|numeric|min:0|max:100',
            'min_absensi_score'  => 'required|numeric|min:0|max:100',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
        ]);

        CompanySlot::create($validated);

        return redirect()->route('admin.companies.show', $request->company_id)
                         ->with('success', 'Gelombang lowongan baru berhasil dibuka!');
    }

    /**
     * Tampilkan form Edit gelombang (Fungsi yang sebelumnya Error/Hilang)
     */
    public function edit(CompanySlot $companySlot)
    {
        $company = $companySlot->company;
        $majors = Major::all();
        $academicYears = AcademicYear::all(); // Tampilkan semua untuk opsi edit

        return view('admin.company_slots.edit', compact('companySlot', 'company', 'majors', 'academicYears'));
    }

    /**
     * Perbarui data gelombang dan KEMBALI ke halaman Detail Perusahaan
     */
    public function update(Request $request, CompanySlot $companySlot)
    {
        $validated = $request->validate([
            'company_id'         => 'required|exists:companies,id', // Hanya di fungsi store
            'academic_year_id'   => 'required|exists:academic_years,id',
            'major_id'           => 'required|exists:majors,id',
            'batch_name'         => 'required|string|max:255',
            'gender_requirement' => 'required|in:L,P,Semua', // <--- Tambahkan baris ini
            'quota'              => 'required|integer|min:1',
            'min_total_score'    => 'required|numeric|min:0|max:100',
            'min_absensi_score'  => 'required|numeric|min:0|max:100',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
        ]);

        $companySlot->update($validated);

        return redirect()->route('admin.companies.show', $companySlot->company_id)
                         ->with('success', 'Data gelombang berhasil diperbarui.');
    }

    /**
     * Hapus gelombang dan KEMBALI ke halaman Detail Perusahaan
     */
    public function destroy(CompanySlot $companySlot)
    {
        $companyId = $companySlot->company_id;
        
        try {
            $companySlot->delete();
            return redirect()->route('admin.companies.show', $companyId)
                             ->with('success', 'Gelombang lowongan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus. Gelombang ini kemungkinan sudah berisi siswa yang diterima.');
        }
    }
}