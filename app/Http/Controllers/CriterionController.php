<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    /**
     * Menampilkan daftar kriteria.
     */
    public function index()
    {
        $criterias = Criterion::all();
        $totalWeight = round((float) $criterias->sum('weight'), 2);
        return view('admin.criterias.index', compact('criterias', 'totalWeight'));
    }

    /**
     * Menampilkan form tambah kriteria.
     */
    public function create()
    {
        return view('admin.criterias.create');
    }

    /**
     * Menyimpan kriteria baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'   => 'required|string|unique:criterias,code',
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:1.00',
            'type'   => 'required|in:benefit,cost',
        ]);

        $currentTotal = round((float) Criterion::sum('weight'), 2);
        $newWeight = round((float) $request->weight, 2);

        // Validasi agar total akumulasi bobot tidak melebihi 1.00 (100%)
        if (($currentTotal + $newWeight) > 1.00) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 1.00 (100%). Total bobot saat ini: ' . number_format($currentTotal, 2) . ', penambahan ini akan menjadi: ' . number_format($currentTotal + $newWeight, 2));
        }

        Criterion::create([
            'code'   => strtolower($request->code),
            'name'   => $request->name,
            'weight' => $newWeight,
            'type'   => $request->type,
        ]);

        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kriteria.
     */
    public function edit($id)
    {
        $criterion = Criterion::findOrFail($id);
        return view('admin.criterias.edit', compact('criterion'));
    }

    /**
     * Memperbarui kriteria di database.
     */
    public function update(Request $request, $id)
    {
        $criterion = Criterion::findOrFail($id);

        $request->validate([
            'code'   => 'required|string|unique:criterias,code,' . $criterion->id,
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:1.00',
            'type'   => 'required|in:benefit,cost',
        ]);

        // Hitung total bobot kriteria lain selain yang sedang diedit
        $othersWeight = round((float) Criterion::where('id', '!=', $criterion->id)->sum('weight'), 2);
        $newWeight = round((float) $request->weight, 2);

        // Validasi akumulasi bobot tidak lebih dari 1.00
        if (($othersWeight + $newWeight) > 1.00) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 1.00 (100%). Total bobot akan menjadi: ' . number_format($othersWeight + $newWeight, 2));
        }

        $criterion->update([
            'code'   => strtolower($request->code),
            'name'   => $request->name,
            'weight' => $newWeight,
            'type'   => $request->type,
        ]);

        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus kriteria dari database.
     */
    public function destroy($id)
    {
        $criterion = Criterion::findOrFail($id);

        // Proteksi: Sistem harus menyisakan minimal 1 kriteria agar mesin SMART tidak error
        if (Criterion::count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus kriteria terakhir. Sistem membutuhkan minimal 1 kriteria untuk dieksekusi.');
        }

        $criterion->delete();
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}