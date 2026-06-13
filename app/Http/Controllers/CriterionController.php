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
        // Mengubah skala bobot dari 0-1 menjadi 0-100 untuk memudahkan tampilan (biasanya user lebih nyaman melihat 20 daripada 0.20)
        // Jika Anda tetap ingin 0-1, silakan sesuaikan di sini.
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
            // PERBAIKAN: Menggunakan tabel 'criteria' (bukan 'criterias')
            'code'   => 'required|string|unique:criteria,code',
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:100', // Asumsi input 1-100
            'type'   => 'required|in:benefit,cost',
        ]);

        $currentTotal = round((float) Criterion::sum('weight'), 2);
        $newWeight = round((float) $request->weight, 2);

        // Validasi agar total akumulasi bobot tidak melebihi 100
        if (($currentTotal + $newWeight) > 100) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 100%. Total saat ini: ' . $currentTotal . '%.');
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
            // PERBAIKAN: Menggunakan tabel 'criteria' (bukan 'criterias')
            'code'   => 'required|string|unique:criteria,code,' . $criterion->id,
            'name'   => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:100',
            'type'   => 'required|in:benefit,cost',
        ]);

        $othersWeight = round((float) Criterion::where('id', '!=', $criterion->id)->sum('weight'), 2);
        $newWeight = round((float) $request->weight, 2);

        if (($othersWeight + $newWeight) > 100) {
            return back()->withInput()->with('error', 'Akumulasi total bobot tidak boleh melebihi 100%. Total bobot akan menjadi: ' . ($othersWeight + $newWeight) . '%.');
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

        if (Criterion::count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus kriteria terakhir.');
        }

        $criterion->delete();
        return redirect()->route('admin.criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}