<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanySlot;
use App\Models\AcademicYear;
use App\Models\Major;
use Illuminate\Http\Request;
use Carbon\Carbon; // Library ajaib untuk hitung tanggal

class CompanySlotController extends Controller
{
    public function index()
    {
        // Mengambil semua data slot lowongan, diurutkan dari yang terbaru
        // dan menyertakan relasi perusahaan dan jurusan agar tidak berat (Eager Loading)
        $slots = CompanySlot::with(['company', 'major', 'academicYear'])->latest()->get();
        
        return view('admin.company_slots.index', compact('slots'));
    }
    // Menampilkan halaman form input lowongan baru
    public function create()
    {
        $companies = Company::all();
        $majors = Major::all();
        // Ambil tahun ajaran yang sedang aktif saja
        $activeYear = AcademicYear::where('is_active', true)->first(); 

        return view('admin.company_slots.create', compact('companies', 'majors', 'activeYear'));
    }

    // Memproses data dari form dan menyimpan ke database
    public function store(Request $request)
    {
        // 1. Validasi input dari Hubin
        $request->validate([
            'company_id'        => 'required|exists:companies,id',
            'major_id'          => 'required|exists:majors,id',
            'batch_name'        => 'required|string|max:255',
            'quota'             => 'required|integer|min:1',
            'min_total_score'   => 'required|numeric|min:0|max:100',
            'min_absensi_score' => 'required|numeric|min:0|max:100',
            'start_date'        => 'required|date',
            'duration_months'   => 'required|integer|in:1,2,3,4,5,6', // Pilihan durasi bulan
        ]);

        // Pastikan ada tahun ajaran yang aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang aktif. Silakan atur terlebih dahulu.');
        }

        // 2. LOGIKA INTI SKRIPSI: Hitung End Date otomatis dengan Carbon
        $startDate = Carbon::parse($request->start_date);
        $duration = (int) $request->duration_months;
        
        // Menggunakan addMonthsNoOverflow agar aman di akhir bulan (misal Februari)
        $endDate = $startDate->copy()->addMonthsNoOverflow($duration);

        // 3. Simpan ke database
        CompanySlot::create([
            'company_id'        => $request->company_id,
            'academic_year_id'  => $activeYear->id,
            'major_id'          => $request->major_id,
            'batch_name'        => $request->batch_name,
            'quota'             => $request->quota,
            'min_total_score'   => $request->min_total_score,
            'min_absensi_score' => $request->min_absensi_score,
            'start_date'        => $startDate->toDateString(),
            'end_date'          => $endDate->toDateString(), // Disimpan otomatis
        ]);

        // 4. Kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.company_slots.index')
                         ->with('success', 'Gelombang lowongan berhasil dibuka. Tanggal penarikan jatuh pada: ' . $endDate->translatedFormat('d F Y'));
    }
}