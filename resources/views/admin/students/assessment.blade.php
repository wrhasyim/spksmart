@extends('layouts.hubin')

@section('title', 'Input Nilai SMART')

@section('content')

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center">
            <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Siswa
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-8">
            <div class="mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Input Nilai Kriteria SMART</h2>
                <p class="text-sm text-gray-500 mt-1">Masukkan nilai evaluasi siswa berdasarkan kriteria yang aktif di sistem.</p>
            </div>
            
            <div class="bg-indigo-50/50 border border-indigo-100 p-5 mb-8 rounded-xl flex flex-col md:flex-row gap-4 justify-between md:items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Siswa Dinilai</p>
                    <p class="text-lg font-bold text-indigo-900">{{ $student->name }}</p>
                </div>
                <div class="flex gap-4 text-sm bg-white px-4 py-2 rounded-lg border border-indigo-50 overflow-x-auto">
                    <div>
                        <span class="text-gray-400 block text-xs">NISN</span>
                        <span class="font-bold text-gray-800">{{ $student->nisn }}</span>
                    </div>
                    <div class="border-l border-gray-200 pl-4">
                        <span class="text-gray-400 block text-xs">Kelas</span>
                        <span class="font-bold text-gray-800">{{ $student->class_name ?? '-' }}</span>
                    </div>
                    <div class="border-l border-gray-200 pl-4">
                        <span class="text-gray-400 block text-xs">Jurusan</span>
                        <span class="font-bold text-indigo-600">{{ $student->major->name ?? $student->major->code ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.students.assessment.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                @php
                    // Ambil data array (JSON) lama jika ada, jika kosong buat array kosong
                    $existingScores = $student->assessment ? $student->assessment->scores_data : [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($criteria as $criterion)
                        @php
                            // Cek jika ada nilai error (old), jika tidak, ambil nilai lama dari DB. Jika tidak ada sama sekali, set 0
                            $val = old('scores.' . $criterion->id, $existingScores[$criterion->id] ?? 0);
                            $isCost = strtolower($criterion->type) === 'cost';
                        @endphp
                        
                        <div class="{{ $isCost ? 'bg-red-50/50 p-4 rounded-xl border border-red-100' : '' }}">
                            <label class="block text-sm font-bold {{ $isCost ? 'text-red-800' : 'text-gray-700' }} mb-2">
                                Nilai {{ $criterion->name }} 
                                <span class="px-2 py-0.5 ml-1 text-[10px] rounded {{ $isCost ? 'bg-red-200 text-red-900' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($criterion->type) }}</span>
                            </label>
                            
                            <input type="number" step="0.01" name="scores[{{ $criterion->id }}]" value="{{ $val }}" min="0" max="100" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-white transition font-bold text-gray-900" required>
                            
                            @if($isCost)
                                <p class="text-xs text-red-600 mt-2 font-medium flex gap-1 items-start leading-tight">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    *Sifat Cost: Angka lebih kecil berarti lebih bagus di perhitungan SPK.
                                </p>
                            @else
                                <p class="text-xs text-gray-500 mt-2 font-medium">*Skala 0-100. Semakin tinggi semakin baik.</p>
                            @endif
                        </div>
                    @endforeach
                    
                    @if($criteria->isEmpty())
                        <div class="col-span-1 md:col-span-2 bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-sm font-medium">
                            ⚠️ Belum ada Kriteria SMART yang diatur. Silakan tambahkan kriteria terlebih dahulu pada menu "Kriteria".
                        </div>
                    @endif
                </div>

                <div class="flex justify-end border-t border-gray-100 pt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg font-bold transition duration-200 w-full md:w-auto" {{ $criteria->isEmpty() ? 'disabled' : '' }}>
                        Simpan Penilaian Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection