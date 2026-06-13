<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;
use App\Models\Placement;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PlacementsExport;
use Maatwebsite\Excel\Facades\Excel;

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

        if ($selectedYearId) {
            // HANYA memuat siswa yang BELUM FINAL / Belum di-ACC
            $placements = Placement::where('academic_year_id', $selectedYearId)
                ->where(function($query) {
                    $query->where('status_pencocokan', '!=', 'final')
                          ->orWhereNull('status_pencocokan');
                })
                ->with(['student.major', 'student.assessment', 'company']) 
                ->paginate(10)
                ->withQueryString();
        }

        return view('admin.placements.index', compact('placements', 'activeYear', 'allYears', 'selectedYearId'));
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

            // Redirect ke halaman Proses SPK Aktif
            return redirect()->route('admin.placements.index')
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
    // FITUR: MANUAL OVERRIDE (PENYESUAIAN MANUAL)
    // ========================================================

    public function edit(Placement $placement)
    {
        $placement->load(['student.major', 'student.assessment', 'company']);
        $companies = Company::orderBy('name', 'asc')->get();
        
        return view('admin.placements.edit', compact('placement', 'companies'));
    }

    public function update(Request $request, Placement $placement)
    {
        $validated = $request->validate([
            'company_id'       => 'nullable|exists:companies,id',
            'kategori_kasus'   => 'required|string|in:Kesehatan/Fisik Mendadak,Pelanggaran Disiplin Berat,Permintaan Khusus Industri,Kendala Darurat Keluarga/Domisili',
            'detail_kronologi' => 'required|string|min:10|max:500'
        ]);

        $rekamJejak = "[INTERVENSI KEPALA HUBIN - KASUS: " . strtoupper($validated['kategori_kasus']) . "] " . $validated['detail_kronologi'];

        $placement->update([
            'company_id'       => $validated['company_id'],
            'notes'            => $rekamJejak,
            'placement_method' => 'MANUAL_OVERRIDE'
        ]);

        if ($validated['company_id']) {
            $placement->student->update(['status' => 'lolos_prakerin']);
        } else {
            $placement->student->update(['status' => 'pembinaan']);
        }

        // Redirect ke halaman Proses SPK Aktif
        return redirect()->route('admin.placements.index')->with('success', 'Intervensi penempatan manual berhasil dicatat dan diterapkan!');
    }

    public function exportExcel(\Illuminate\Http\Request $request)
    {
        $academicYearId = $request->get('academic_year_id', \App\Models\AcademicYear::where('is_active', true)->first()->id ?? 1);
        $filename = 'Rekap_Penempatan_Prakerin_Periode_' . date('Y_m_d_His') . '.xlsx';
        return Excel::download(new PlacementsExport($academicYearId), $filename);
    }

    // ========================================================
    // FITUR: RIWAYAT & ACC HUBIN
    // ========================================================

    public function history()
    {
        // HANYA memuat data yang sudah FINAL (Di-ACC)
        $history = Placement::with(['student', 'company', 'academicYear'])
                    ->where('status_pencocokan', 'final')
                    ->latest()
                    ->paginate(20);
                    
        return view('admin.placements.history', compact('history'));
    }

    public function approve(Placement $placement)
    {
        $placement->load(['companySlot', 'student']);
        $slot = $placement->companySlot;

        if (!$slot) {
            return back()->with('error', 'Gagal! Data alokasi kuota industri tidak ditemukan.');
        }

        if ($slot->quota <= 0) {
            return back()->with('error', 'Gagal ACC! Kuota utama industri ini sudah terisi penuh.');
        }

        // Kunci penempatan agar kebal dari generate ulang, jadikan final!
        $placement->update([
            'placement_method'  => 'SYSTEM_APPROVED',
            'status_pencocokan' => 'final'
        ]);

        // Kunci status siswa 
        if ($placement->student) {
            $placement->student->update(['status' => 'lolos_prakerin']);
        }

        // Potong kuota secara permanen di database
        $slot->decrement('quota');

        return back()->with('success', "Penempatan siswa {$placement->student->name} berhasil di-ACC! Data telah dipindahkan ke Riwayat.");
    }
}