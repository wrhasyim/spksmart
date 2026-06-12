@extends('layouts.hubin')

@section('title', 'Tambah Gelombang Lowongan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Buka Gelombang Lowongan Baru</h1>
    <p class="text-sm text-gray-500 mb-6">Industri: <b class="text-indigo-600">{{ $company->name }}</b></p>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.company_slots.store') }}" method="POST">
        @csrf
        <input type="hidden" name="company_id" value="{{ $company->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Nama Gelombang <span class="text-red-500">*</span></label>
                <input type="text" name="batch_name" value="{{ old('batch_name') }}" placeholder="Contoh: Gelombang 1 - 2026" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Periode Tahun Ajaran <span class="text-red-500">*</span></label>
                <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Target Jurusan <span class="text-red-500">*</span></label>
                <select name="major_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                            {{ $major->name }} ({{ $major->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Syarat Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="gender_requirement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="Semua" {{ old('gender_requirement') == 'Semua' ? 'selected' : '' }}>Semua Jenis Kelamin</option>
                    <option value="L" {{ old('gender_requirement') == 'L' ? 'selected' : '' }}>Khusus Laki-laki (L)</option>
                    <option value="P" {{ old('gender_requirement') == 'P' ? 'selected' : '' }}>Khusus Perempuan (P)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Mulai Pendaftaran <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Tutup Pendaftaran <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="md:col-span-2 border-t pt-4 mt-2">
                <h3 class="text-base font-bold text-gray-900 mb-4">Pengaturan Kuota & Passing Grade</h3>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Kuota Diterima <span class="text-red-500">*</span></label>
                <input type="number" name="quota" value="{{ old('quota', 1) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Minimal Nilai Absensi (Mutlak) <span class="text-red-500">*</span></label>
                <input type="number" name="min_absensi_score" value="{{ old('min_absensi_score', 0) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Minimal Skor Total SMART <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="min_total_score" value="{{ old('min_total_score', 0) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <p class="text-xs text-gray-500 mt-1">*Siswa yang nilai kalkulasi SMART-nya di bawah batas ini otomatis gagal di perusahaan ini.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
            <a href="{{ route('admin.companies.show', $company->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg font-bold transition duration-150">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-bold shadow transition duration-150">Simpan Gelombang</button>
        </div>
    </form>
</div>
@endsection