<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkController extends Controller
{
    protected $smartEngine;

    public function __construct(SmartEngineService $smartEngine)
    {
        $this->smartEngine = $smartEngine;
    }

    /**
     * Tampilkan Dashboard Utama Rekomendasi Penempatan (Terbagi per 5 data per halaman)
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Ambil ID tahun ajaran dari parameter GET, atau gunakan tahun ajaran aktif sebagai default
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        $allYears = AcademicYear::all();
        
        $placements = [];
        $chartData = [];

        if ($selectedYearId) {
            // Membatasi tabel menjadi 5 siswa per halaman dan mempertahankan query string filter
            $placements = Placement::where('academic_year_id', $selectedYearId)
                ->with(['student.major', 'company'])
                ->paginate(5)
                ->withQueryString();

            // Mengambil data untuk grafik batang (Chart) tanpa pagination
            $chartData = Placement::select('company_id', DB::raw('count(*) as total'))
                ->whereNotNull('company_id')
                ->where('academic_year_id', $selectedYearId)
                ->with('company')
                ->groupBy('company_id')
                ->get();
        }

        return view('admin.placements.index', compact('placements', 'activeYear', 'chartData', 'allYears', 'selectedYearId'));
    }

    /**
     * Jalankan Mesin Kalkulasi Matchmaking Algoritma SMART
     */
    public function generate(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang sedang aktif.');
        }

        try {
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->route('dashboard')
                             ->with('success', 'Kalkulasi SPK dan pencocokan industri berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Cetak Dokumen Hasil Rekomendasi ke format PDF (Ukuran Kertas A4 - Landscape)
     */
    public function printPdf(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);

        if (!$selectedYearId) {
            return back()->with('error', 'Tidak ada data Tahun Ajaran untuk dicetak.');
        }

        // Ambil semua data siswa yang ditempatkan (tanpa pagination) khusus untuk dicetak ke PDF
        $placements = Placement::where('academic_year_id', $selectedYearId)
            ->with(['student.major', 'company'])
            ->orderBy('final_smart_score', 'desc')
            ->get();

        $selectedYear = AcademicYear::find($selectedYearId);

        // Render struktur HTML blade ke dalam bentuk PDF murni
        $pdf = Pdf::loadView('admin.placements.pdf', compact('placements', 'selectedYear'));
        
        // Atur orientasi kertas menjadi Landscape agar tabel kolom muat dengan lega
        $pdf->setPaper('a4', 'landscape');

        // Proteksi: Ubah karakter ilegal '/' atau '\' dari nama tahun ajaran (misal 2026/2027 menjadi 2026-2027)
        $safeYearName = str_replace(['/', '\\'], '-', $selectedYear->name);

        // Alirkan stream PDF langsung ke browser peninjau
        return $pdf->stream('Laporan_Penempatan_Prakerin_' . $safeYearName . '.pdf');
    }
}