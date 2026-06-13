<?php

namespace App\Http\Controllers;

use App\Models\CompanySlot;
use App\Models\AcademicYear;
use App\Models\Company;
use App\Models\Major;
use Illuminate\Http\Request;

class CompanySlotController extends Controller
{
    /**
     * Tampilkan daftar slot perusahaan
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        $allYears = AcademicYear::all();

        $slots = CompanySlot::with(['company', 'majors'])
            ->where('academic_year_id', $selectedYearId)
            ->latest()
            ->get();

        return view('admin.company_slots.index', compact('slots', 'allYears', 'selectedYearId'));
    }

    /**
     * Menyimpan Kuota / Gelombang Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id'         => 'required|exists:companies,id',
            'academic_year_id'   => 'required|exists:academic_years,id',
            'batch_name'         => 'required|string',
            'quota'              => 'required|integer',
            'gender_requirement' => 'required',
            'min_total_score'    => 'required|numeric',
            'min_absensi_score'  => 'required|numeric',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date',
            'major_ids'          => 'required|array',
            'major_ids.*'        => 'exists:majors,id',
        ]);

        // Ambil data untuk tabel utama (kecuali major_ids)
        $data = $request->except(['major_ids', '_token']);

        // Simpan ke tabel company_slots
        $slot = CompanySlot::create($data);

        // Sync ke tabel pivot company_slot_major
        $slot->majors()->sync($request->major_ids);

        return redirect()->back()->with('success', 'Kuota berhasil dibuka!');
    }

    /**
     * Memperbarui Kuota
     */
    public function update(Request $request, CompanySlot $companySlot)
    {
        $validated = $request->validate([
            'batch_name'         => 'required|string',
            'quota'              => 'required|integer',
            'gender_requirement' => 'required',
            'min_total_score'    => 'required|numeric',
            'min_absensi_score'  => 'required|numeric',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date',
            'major_ids'          => 'required|array',
            'major_ids.*'        => 'exists:majors,id',
        ]);

        $data = $request->except(['major_ids', '_token', '_method']);
        
        $companySlot->update($data);
        
        // Update tabel pivot
        $companySlot->majors()->sync($request->major_ids);

        return redirect()->back()->with('success', 'Data kuota diperbarui.');
    }

    /**
     * Menghapus Kuota
     */
    public function destroy(CompanySlot $company_slot)
    {
        // Menghapus data juga akan melepas relasi di tabel pivot otomatis 
        // jika migrasi diatur onDelete('cascade')
        $company_slot->delete();
        
        return back()->with('success', 'Alokasi kuota tersebut berhasil dihapus.');
    }
}