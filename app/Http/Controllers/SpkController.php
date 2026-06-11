<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartEngineService;
use App\Models\AcademicYear;

class SpkController extends Controller
{
    protected $smartEngine;

    // Dependency Injection
    public function __construct(SmartEngineService $smartEngine)
    {
        $this->smartEngine = $smartEngine;
    }

    /**
     * Eksekusi Kalkulasi SPK oleh Admin
     */
    public function generatePlacement(Request $request)
    {
        // Pastikan hanya admin yang bisa mengeksekusi
        $this->authorize('run-spk'); 

        // Ambil tahun ajaran yang sedang aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        try {
            // Panggil Service Engine
            $this->smartEngine->runMatchmaking($activeYear->id);

            return redirect()->back()->with('success', 'Kalkulasi SPK berhasil dieksekusi. Hasil penempatan telah diperbarui.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat kalkulasi: ' . $e->getMessage());
        }
    }
}