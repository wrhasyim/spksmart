<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;
use App\Models\Placement;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SpkController extends Controller
{
    protected $smartEngine;

    public function __construct(SmartEngineService $smartEngine)
    {
        $this->smartEngine = $smartEngine;
    }

    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        $allYears = AcademicYear::all();
        
        $placements = [];
        $chartData = [];

        if ($selectedYearId) {
            $placements = Placement::where('academic_year_id', $selectedYearId)
                // TAMBAHKAN 'student.assessment' DI SINI
                ->with(['student.major', 'student.assessment', 'company']) 
                ->paginate(5)
                ->withQueryString();

            $chartData = Placement::select('company_id', DB::raw('count(*) as total'))
                ->whereNotNull('company_id')
                ->where('academic_year_id', $selectedYearId)
                ->with('company')
                ->groupBy('company_id')
                ->get();
        }

        return view('admin.placements.index', compact('placements', 'activeYear', 'chartData', 'allYears', 'selectedYearId'));
    }

    public function generate(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang sedang aktif.');
        }

        $studentsWithoutAssessment = \App\Models\Student::where('academic_year_id', $activeYear->id)
                                        ->doesntHave('assessment')
                                        ->count();

        if ($studentsWithoutAssessment > 0) {
            return back()->with('error', 'Gagal memproses! Terdapat ' . $studentsWithoutAssessment . ' siswa yang belum memiliki nilai. Harap lengkapi nilai seluruh siswa terlebih dahulu di menu Data Siswa.');
        }

        try {
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->route('dashboard')
                             ->with('success', 'Kalkulasi SPK dan pencocokan industri berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function printPdf(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);

        if (!$selectedYearId) {
            return back()->with('error', 'Tidak ada data Tahun Ajaran untuk dicetak.');
        }

        $placements = Placement::where('academic_year_id', $selectedYearId)
            ->with(['student.major', 'company'])
            ->orderBy('final_smart_score', 'desc')
            ->get();

        $selectedYear = AcademicYear::find($selectedYearId);

        $pdf = Pdf::loadView('admin.placements.pdf', compact('placements', 'selectedYear'));
        $pdf->setPaper('a4', 'landscape');

        $safeYearName = str_replace(['/', '\\'], '-', $selectedYear->name);

        return $pdf->stream('Laporan_Penempatan_Prakerin_' . $safeYearName . '.pdf');
    }
    
    public function printLetter(Placement $placement)
    {
        if (!$placement->company_id) {
            return back()->with('error', 'Surat tidak dapat dicetak karena siswa belum mendapatkan penempatan.');
        }

        $placement->load(['student.major', 'company', 'academicYear']);

        $pdf = Pdf::loadView('admin.placements.letter', compact('placement'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Surat_Pengantar_' . str_replace(' ', '_', $placement->student->name) . '.pdf');
    }

    // ========================================================
    // FITUR BARU: MANUAL OVERRIDE (PENYESUAIAN MANUAL)
    // ========================================================

    /**
     * Menampilkan form edit untuk penyesuaian manual penempatan
     */
    public function edit(Placement $placement)
    {
        $placement->load(['student.major', 'student.assessment', 'company']);
        $companies = Company::orderBy('name', 'asc')->get();
        
        return view('admin.placements.edit', compact('placement', 'companies'));
    }

    /**
     * Menyimpan hasil perubahan manual penempatan ke database
     */
    public function update(Request $request, Placement $placement)
    {
        $validated = $request->validate([
            'company_id'       => 'nullable|exists:companies,id',
            'kategori_kasus'   => 'required|string|in:Kesehatan/Fisik Mendadak,Pelanggaran Disiplin Berat,Permintaan Khusus Industri,Kendala Darurat Keluarga/Domisili',
            'detail_kronologi' => 'required|string|min:10|max:500' // Wajib diisi minimal 10 karakter
        ]);

        // Format ulang catatan agar menjadi rekam jejak intervensi yang resmi
        $rekamJejak = "[INTERVENSI KEPALA HUBIN - KASUS: " . strtoupper($validated['kategori_kasus']) . "] " . $validated['detail_kronologi'];

        // Simpan perubahan ke tabel placement dan ubah status metode menjadi manual
        $placement->update([
            'company_id'       => $validated['company_id'],
            'notes'            => $rekamJejak,
            'placement_method' => 'MANUAL_OVERRIDE'
        ]);

        // Sesuaikan kembali status siswa di tabel students
        if ($validated['company_id']) {
            $placement->student->update(['status' => 'lolos_prakerin']);
        } else {
            $placement->student->update(['status' => 'pembinaan']);
        }

        return redirect()->route('dashboard')->with('success', 'Intervensi penempatan manual berhasil dicatat dan diterapkan!');
    }
}