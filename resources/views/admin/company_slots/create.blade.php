@extends('layouts.hubin')

@section('title', 'Tambah Kuota Lowongan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali
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

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 border border-gray-100">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Form Buka Lowongan Prakerin</h2>
                <p class="text-sm text-gray-500 mt-1">Tentukan kuota dan syarat jurusan untuk perusahaan terpilih.</p>
            </div>

            <form action="{{ route('admin.company-slots.store') }}" method="POST">
                @csrf
                
                @if(request('company_id'))
                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                @else
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Perusahaan <span class="text-red-500">*</span></label>
                        <select name="company_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach(\App\Models\Company::all() as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select name="academic_year_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                            @foreach(\App\Models\AcademicYear::orderBy('name', 'desc')->get() as $year)
                                <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Gelombang <span class="text-red-500">*</span></label>
                        <input type="text" name="batch_name" placeholder="Contoh: Gelombang 1 / Reguler" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Kuota Siswa <span class="text-red-500">*</span></label>
                        <input type="number" name="quota" min="1" placeholder="Contoh: 5" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Persyaratan Gender <span class="text-red-500">*</span></label>
                        <select name="gender_requirement" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                            <option value="Semua">Semua Gender</option>
                            <option value="L">Khusus Laki-laki</option>
                            <option value="P">Khusus Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Min. Skor SMART <span class="text-red-500">*</span></label>
                        <input type="number" name="min_total_score" step="0.01" value="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Min. Skor Absensi <span class="text-red-500">*</span></label>
                        <input type="number" name="min_absensi_score" step="0.01" value="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50" required>
                    </div>
                </div>

                <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-900 mb-3">Jurusan yang Diterima <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-4">Pilih satu atau lebih jurusan yang diperbolehkan melamar ke lowongan ini.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(\App\Models\Major::all() as $major)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg bg-white hover:bg-indigo-50 cursor-pointer transition shadow-sm">
                                <input type="checkbox" name="major_ids[]" value="{{ $major->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-5 w-5">
                                <span class="ml-3 text-sm font-medium text-gray-700">{{ $major->abbreviation ?? $major->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg font-bold transition duration-200 w-full md:w-auto">
                        Simpan Lowongan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection