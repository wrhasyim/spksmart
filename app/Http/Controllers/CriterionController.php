<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    public function index()
    {
        $criterias = Criterion::all();
        return view('admin.criterias.index', compact('criterias'));
    }

    public function update(Request $request)
    {
        $weights = $request->input('weights'); // array asosiatif [id => bobot]
        
        // Validasi total bobot wajib 1.00 (toleransi pembulatan 0.01)
        $totalWeight = array_sum($weights);
        if (abs($totalWeight - 1.00) > 0.01) {
            return back()->with('error', 'Akumulasi total bobot kriteria harus tepat berjumlah 1.00 (100%). Total bobot saat ini: ' . $totalWeight);
        }

        // Simpan pembaruan bobot ke database
        foreach ($weights as $id => $weight) {
            $criterion = Criterion::findOrFail($id);
            $criterion->weight = $weight;
            $criterion->save();
        }

        return redirect()->route('admin.criterias.index')->with('success', 'Manajemen bobot kriteria metode SMART berhasil diperbarui.');
    }
}