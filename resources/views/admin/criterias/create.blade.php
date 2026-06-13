@extends('layouts.hubin')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="mb-8">
    <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
        Tambah Kriteria Baru
    </h2>
</div>

<div class="bg-white overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-3xl border border-gray-100 p-8 max-w-3xl mx-auto">
    
    <form action="{{ route('admin.criterias.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Kriteria</label>
            <input type="text" name="code" value="{{ old('code') }}" required 
                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-gray-50/50" 
                placeholder="Contoh: C1, K1, ABSENSI">
            @error('code') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kriteria</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-gray-50/50" 
                placeholder="Contoh: Nilai Rata-rata Raport">
            @error('name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kriteria</label>
                <select name="type" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-white">
                    <option value="benefit" {{ old('type') == 'benefit' ? 'selected' : '' }}>Benefit (Makin besar makin baik)</option>
                    <option value="cost" {{ old('type') == 'cost' ? 'selected' : '' }}>Cost (Makin kecil makin baik)</option>
                </select>
                @error('type') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bobot Kriteria</label>
                <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" required 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-gray-50/50" 
                    placeholder="Contoh: 30">
                @error('weight') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 pt-6 mt-6">
            <a href="{{ route('admin.criterias.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition text-sm">
                Batal
            </a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition duration-200 shadow-md shadow-indigo-600/20 text-sm">
                Simpan Kriteria
            </button>
        </div>
    </form>

</div>
@endsection