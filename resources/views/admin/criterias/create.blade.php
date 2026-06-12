@extends('layouts.hubin')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <div class="mb-6">
        <a href="{{ route('admin.criterias.index') }}" class="text-indigo-600 hover:underline font-bold text-sm">&larr; Kembali</a>
    </div>

    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Tambah Kriteria Baru</h1>
    <p class="text-xs text-gray-500 mb-6">Pastikan kode konsisten dengan properti penilaian siswa di database.</p>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded text-sm font-bold shadow-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-xs">
            <ul class="list-disc pl-5 font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.criterias.store') }}" method="POST">
        @csrf

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kode / Kunci Kolom (DB) <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: absensi" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" required>
            <span class="text-[10px] text-gray-400 block mt-1">Gunakan huruf kecil dan underscore (_), tidak boleh pakai spasi.</span>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Tampilan Kriteria <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Nilai Absensi" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Sifat Kriteria <span class="text-red-500">*</span></label>
            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option value="" disabled selected>-- Pilih Sifat --</option>
                <option value="benefit" {{ old('type') == 'benefit' ? 'selected' : '' }}>BENEFIT (Makin tinggi makin baik)</option>
                <option value="cost" {{ old('type') == 'cost' ? 'selected' : '' }}>COST (Makin rendah makin baik)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Bobot (Contoh: 0.30 untuk 30%) <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0.01" max="1.00" name="weight" value="{{ old('weight') }}" placeholder="0.01 - 1.00" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" required>
            <span class="text-[10px] text-gray-400 block mt-1">Total akumulasi bobot semua kriteria tidak boleh melebihi 1.00 (100%).</span>
        </div>

        <div class="flex justify-end gap-3 border-t pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition text-sm">Simpan Kriteria</button>
        </div>
    </form>
</div>
@endsection