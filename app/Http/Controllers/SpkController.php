<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;
use App\Models\Placement;
use Illuminate\Support\Facades\DB;

class SpkController extends Controller
{
    protected $smartEngine;

    public function __construct(SmartEngineService $smartEngine)
    {
        $this->smartEngine = $smartEngine;
    }

    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $placements = [];
        $chartData = [];

        if ($activeYear) {
            $placements = Placement::where('academic_year_id', $activeYear->id)
                ->with(['student.major', 'company'])
                ->get();

            // Query untuk mengambil data grafik: jumlah siswa per perusahaan
            $chartData = Placement::select('company_id', DB::raw('count(*) as total'))
                ->whereNotNull('company_id')
                ->where('academic_year_id', $activeYear->id)
                ->with('company')
                ->groupBy('company_id')
                ->get();
        }

        return view('admin.placements.index', compact('placements', 'activeYear', 'chartData'));
    }

    public function generate(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran yang sedang aktif.');
        }

        try {
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->route('admin.placements.index')
                             ->with('success', 'Kalkulasi SPK dan pencocokan industri berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}