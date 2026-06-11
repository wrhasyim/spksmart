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

    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Ambil ID tahun ajaran dari parameter GET, atau gunakan tahun ajaran aktif sebagai default
        $selectedYearId = $request->get('academic_year_id', $activeYear ? $activeYear->id : null);
        
        $allYears = AcademicYear::all();
        
        $placements = [];
        $chartData = [];

        if ($selectedYearId) {
            $placements = Placement::where('academic_year_id', $selectedYearId)
                ->with(['student.major', 'company'])
                ->get();

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

        try {
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->route('admin.placements.index')
                             ->with('success', 'Kalkulasi SPK dan pencocokan industri berhasil diselesaikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}