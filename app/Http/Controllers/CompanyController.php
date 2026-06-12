<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Tampilkan daftar perusahaan (Master Data murni, tanpa filter tahun ajaran)
     */
    public function index(Request $request)
    {
        // Mengambil data perusahaan master dengan pagination
        $companies = Company::orderBy('name', 'asc')->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Tampilkan form untuk menambah perusahaan baru
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Simpan data perusahaan baru ke database
     */
    public function store(Request $request)
    {
        // Validasi HANYA UNTUK KOLOM YANG ADA DI TABEL companies
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Perusahaan mitra berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengubah data perusahaan
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Perbarui data perusahaan yang ada di database
     */
    public function update(Request $request, Company $company)
    {
        // Validasi HANYA UNTUK KOLOM YANG ADA DI TABEL companies
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
                         ->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Hapus data perusahaan dari database
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return redirect()->route('admin.companies.index')
                             ->with('success', 'Data perusahaan berhasil dihapus.');
        } catch (\Exception $e) {
            // Proteksi jika perusahaan tidak bisa dihapus karena sudah dipakai di tabel company_slots
            return back()->with('error', 'Perusahaan tidak dapat dihapus karena masih terhubung dengan data Gelombang/Lowongan.');
        }
    }
}