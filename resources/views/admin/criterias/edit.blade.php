@extends('layouts.hubin')

@section('title', 'Edit Kriteria')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <div class="mb-6">
        <a href="{{ route('admin.criterias.index') }}" class="text-indigo-600 hover:underline font-bold text-sm">&larr; Kembali</a>
    </div>

    <h1 class="text-2xl font-extrabold text-gray-900 mb-6">Edit Kriteria: <span class="text-indigo-600 uppercase">{{ $criterion->code }}</span></h1>

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

    <form action="{{ route('admin.criterias.update', ['criteria' => $criterion->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Kode / Kunci Kolom (DB) <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code', $criterion->code) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" required>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Tampilan Kriteria <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $criterion->name) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
        </div>

        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-1">Sifat Kriteria <span class="text-red-500">*</span></label>
            <select name="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                <option value="benefit" {{ old('type', $criterion->type) == 'benefit' ? 'selected' : '' }}>BENEFIT (Makin tinggi makin baik)</option>
                <option value="cost" {{ old('type', $criterion->type) == 'cost' ? 'selected' : '' }}>COST (Makin rendah makin baik)</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Bobot (Skor 0.01 - 1.00) <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0.01" max="1.00" name="weight" value="{{ old('weight', $criterion->weight) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-mono" required>
        </div>

        <div class="flex justify-end gap-3 border-t pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm transition text-sm">Perbarui Kriteria</button>
        </div>
    </form>
</div>
@endsection