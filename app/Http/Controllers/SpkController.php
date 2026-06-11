<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;
use App\Models\Placement;

class SpkController extends Controller
{
    protected $smartEngine;

    public function __construct(SmartEngineService $smartEngine)
    {
        $this->smartEngine = $smartEngine;
    }

    /**
     * Halaman Dashboard Hasil Penempatan (Bisa diakses oleh Hubin)
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Ambil data penempatan yang sudah diproses beserta relasi student & company
        $placements = [];
        if ($activeYear) {
            $placements = Placement::where('academic_year_id', $activeYear->id)
                ->with(['student.major', 'company'])
                ->get();
        }

        return view('admin.placements.index', compact('placements', 'activeYear'));
    }

    /**
     * Tombol Eksekusi Mesin SMART
     */
    public function generate(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang sedang aktif.');
        }

        try {
            // Panggil Service Engine
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->route('admin.placements.index')
                             ->with('success', 'Kalkulasi SPK dan pencocokan industri berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}