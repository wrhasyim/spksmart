<?php

namespace App\Http\Controllers;

use App\Models\CompanySlot;
use Illuminate\Http\Request;

class CompanySlotController extends Controller
{
    /**
     * Menyimpan Kuota / Gelombang Baru ke Database
     */
    public function store(Request $request)
    {
        // 1. Validasi semua inputan dari form modal
        $validated = $request->validate([
            'company_id'         => 'required|exists:companies,id',
            'academic_year_id'   => 'required|exists:academic_years,id',
            'major_id'           => 'required|exists:majors,id',
            'batch_name'         => 'required|string|max:255',
            'gender_requirement' => 'required|in:L,P,Semua',
            'quota'              => 'required|integer|min:1',
            'min_total_score'    => 'required|numeric|min:0',
            'min_absensi_score'  => 'required|numeric|min:0',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Logika Efisiensi Kuota (Sesuai Konsep Fleksibel)
        if ($validated['gender_requirement'] === 'L') {
            $validated['quota_male']   = $validated['quota'];
            $validated['quota_female'] = 0;
            
        } elseif ($validated['gender_requirement'] === 'P') {
            $validated['quota_male']   = 0;
            $validated['quota_female'] = $validated['quota'];
            
        } else {
            // Jika "Semua", kita tidak membedakan gender. 
            // Mesin SPK akan murni bergantung pada kolom 'quota' utama.
            $validated['quota_male']   = 0;
            $validated['quota_female'] = 0;
        }

        // 3. Simpan ke database
        CompanySlot::create($validated);

        return back()->with('success', 'Gelombang/Kuota berhasil dibuka! Sistem siap memproses seleksi.');
    }

    /**
     * Menghapus Kuota
     */
    public function destroy(CompanySlot $company_slot)
    {
        $company_slot->delete();
        
        return back()->with('success', 'Alokasi kuota tersebut berhasil dihapus.');
    }
}