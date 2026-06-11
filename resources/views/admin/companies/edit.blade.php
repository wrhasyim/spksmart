@extends('layouts.hubin')

@section('title', 'Ubah Perusahaan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Ubah Data Perusahaan Mitra</h1>

    <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Kuota Siswa (Diterima)</label>
                <input type="number" name="quota" value="{{ old('quota', $company->quota) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="address" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('address', $company->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jurusan / Bidang</label>
                <select name="major_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ old('major_id', $company->major_id) == $major->id ? 'selected' : '' }}>
                            {{ $major->name }} ({{ $major->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Syarat Gender</label>
                <select name="gender_requirement" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    <option value="ALL" {{ old('gender_requirement', $company->gender_requirement) == 'ALL' ? 'selected' : '' }}>Bebas (L / P)</option>
                    <option value="L" {{ old('gender_requirement', $company->gender_requirement) == 'L' ? 'selected' : '' }}>Khusus Laki-laki</option>
                    <option value="P" {{ old('gender_requirement', $company->gender_requirement) == 'P' ? 'selected' : '' }}>Khusus Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Periode Tahun Ajaran</label>
                <select name="academic_year_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id', $company->academic_year_id) == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Passing Grade / Nilai Minimal Kriteria SMART</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-xs font-medium text-gray-600">Nilai Minimal Total</label>
                <input type="number" step="0.01" name="min_total_score" value="{{ old('min_total_score', $company->min_total_score) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Passing Grade Absensi</label>
                <input type="number" step="0.01" name="min_absensi_score" value="{{ old('min_absensi_score', $company->min_absensi_score) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Passing Grade Fisik</label>
                <input type="number" step="0.01" name="min_fisik_score" value="{{ old('min_fisik_score', $company->min_fisik_score) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Passing Grade Keaktifan</label>
                <input type="number" step="0.01" name="min_keaktifan_score" value="{{ old('min_keaktifan_score', $company->min_keaktifan_score) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Passing Grade Kasus/Catatan (Cost)</label>
                <input type="number" step="0.01" name="min_administrasi_score" value="{{ old('min_administrasi_score', $company->min_administrasi_score) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.companies.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded font-medium">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded font-bold shadow">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection