<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::latest()->get();
        return view('admin.majors.index', compact('majors'));
    }

    public function create()
    {
        return view('admin.majors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:majors,code',
            'name' => 'required|string|max:255',
        ], [
            'code.unique' => 'Kode jurusan ini sudah digunakan.',
        ]);

        Major::create($request->all());

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan baru berhasil ditambahkan.');
    }

    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }

    public function update(Request $request, Major $major)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:majors,code,' . $major->id,
            'name' => 'required|string|max:255',
        ]);

        $major->update($request->all());

        return redirect()->route('admin.majors.index')->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        // Proteksi: Jangan hapus jika ada siswa di jurusan ini
        if ($major->students()->count() > 0) {
            return redirect()->route('admin.majors.index')->with('error', 'Gagal! Jurusan ini tidak bisa dihapus karena masih digunakan oleh data siswa.');
        }

        // Proteksi: Jangan hapus jika ada slot perusahaan yang butuh jurusan ini
        if ($major->companySlots()->count() > 0) {
            return redirect()->route('admin.majors.index')->with('error', 'Gagal! Jurusan ini masih terkait dengan syarat lowongan di Perusahaan Mitra.');
        }

        $major->delete();

        return redirect()->route('admin.majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}