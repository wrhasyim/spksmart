<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    public function index()
    {
        $criterias = Criterion::all();
        $totalWeight = $criterias->sum('weight');
        return view('admin.criterias.index', compact('criterias', 'totalWeight'));
    }

    public function create()
    {
        return view('admin.criterias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'   => 'required|string|unique:criterias,code',
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:1.00',
            'type'   => 'required|in:benefit,cost',
        ]);

        // Proteksi: Total bobot tidak boleh lebih dari 1.00 (100%)
        $currentTotalWeight = Criterion::sum('weight');
        if (($currentTotalWeight + $request->weight) > 1.00) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 1.00 (100%). Total bobot saat ini: ' . $currentTotalWeight);
        }

        Criterion::create([
            'code'   => strtolower($request->code),
            'name'   => $request->name,
            'weight' => $request->weight,
            'type'   => $request->type,
        ]);

        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criterion $criterion)
    {
        return view('admin.criterias.edit', compact('criterion'));
    }

    public function update(Request $request, Criterion $criterion)
    {
        $request->validate([
            'code'   => 'required|string|unique:criterias,code,' . $criterion->id,
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:1.00',
            'type'   => 'required|in:benefit,cost',
        ]);

        // Proteksi: Total bobot kriteria lain ditambah bobot ini tidak boleh lebih dari 1.00
        $otherWeights = Criterion::where('id', '!=', $criterion->id)->sum('weight');
        if (($otherWeights + $request->weight) > 1.00) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 1.00 (100%). Total bobot kriteria lain saat ini: ' . $otherWeights);
        }

        $criterion->update([
            'code'   => strtolower($request->code),
            'name'   => $request->name,
            'weight' => $request->weight,
            'type'   => $request->type,
        ]);

        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criterion $criterion)
    {
        $criterion->delete();
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}