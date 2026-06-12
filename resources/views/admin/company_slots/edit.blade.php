@extends('layouts.hubin')

@section('title', 'Ubah Gelombang Lowongan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Ubah Gelombang: {{ $companySlot->batch_name }}</h1>
    <p class="text-sm text-gray-500 mb-6">Industri: <b>{{ $company->name }}</b></p>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.company_slots.update', $companySlot->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="company_id" value="{{ $companySlot->company_id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Nama Gelombang <span class="text-red-500">*</span></label>
                <input type="text" name="batch_name" value="{{ old('batch_name', $companySlot->batch_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Periode Tahun Ajaran <span class="text-red-500">*</span></label>
                <select name="academic_year_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ (old('academic_year_id', $companySlot->academic_year_id) == $year->id) ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Target Jurusan <span class="text-red-500">*</span></label>
                <select name="major_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @foreach($majors as $major)
                        <option value="{{ $major->id }}" {{ (old('major_id', $companySlot->major_id) == $major->id) ? 'selected' : '' }}>
                            {{ $major->name }} ({{ $major->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Syarat Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="gender_requirement" class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="Semua" {{ old('gender_requirement', $companySlot->gender_requirement) == 'Semua' ? 'selected' : '' }}>Semua Jenis Kelamin</option>
                    <option value="L" {{ old('gender_requirement', $companySlot->gender_requirement) == 'L' ? 'selected' : '' }}>Khusus Laki-laki (L)</option>
                    <option value="P" {{ old('gender_requirement', $companySlot->gender_requirement) == 'P' ? 'selected' : '' }}>Khusus Perempuan (P)</option>
                </select>
            </div>

            @php
                $startDate = \Carbon\Carbon::parse($companySlot->start_date);
                $endDate = \Carbon\Carbon::parse($companySlot->end_date);
                $currentDuration = $startDate->diffInMonths($endDate);
                if ($currentDuration == 0) $currentDuration = 1;
            @endphp

            <div class="md:col-span-2 border-t pt-4 mt-2">
                <h3 class="text-base font-bold text-gray-900 mb-4">Jadwal Pemberangkatan & Durasi</h3>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Tanggal Pemberangkatan <span class="text-red-500">*</span></label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $companySlot->start_date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required onchange="calculateEndDate()">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Durasi Prakerin (Bulan) <span class="text-red-500">*</span></label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <input type="number" id="duration" name="duration" value="{{ old('duration', $currentDuration) }}" min="1" max="24" class="block w-full rounded-md border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500" required oninput="calculateEndDate()">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-500 sm:text-sm font-bold">Bulan</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Tanggal Selesai (Dihitung Otomatis)</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $companySlot->end_date) }}" class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 cursor-not-allowed font-bold text-gray-700" required readonly tabindex="-1">
                <p class="text-xs text-indigo-600 mt-1 font-medium">*Tanggal selesai akan otomatis bergeser jika Durasi atau Tanggal Pemberangkatan diubah.</p>
            </div>

            <div class="md:col-span-2 border-t pt-4 mt-2">
                <h3 class="text-base font-bold text-gray-900 mb-4">Pengaturan Kuota & Passing Grade</h3>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Kuota Diterima <span class="text-red-500">*</span></label>
                <input type="number" name="quota" value="{{ old('quota', $companySlot->quota) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700">Minimal Nilai Absensi (Syarat Mutlak) <span class="text-red-500">*</span></label>
                <input type="number" name="min_absensi_score" value="{{ old('min_absensi_score', $companySlot->min_absensi_score) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Minimal Skor Total SMART <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="min_total_score" value="{{ old('min_total_score', $companySlot->min_total_score) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <p class="text-xs text-gray-500 mt-1">*Siswa yang nilai kalkulasi SMART-nya di bawah batas ini otomatis gagal di perusahaan ini.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
            <a href="{{ route('admin.companies.show', $company->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-lg font-bold transition duration-150">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-bold shadow transition duration-150">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        calculateEndDate();
    });

    function calculateEndDate() {
        let startVal = document.getElementById('start_date').value;
        let durationVal = document.getElementById('duration').value;
        
        if(startVal && durationVal) {
            let date = new Date(startVal);
            let duration = parseInt(durationVal);
            
            // Tambahkan bulan secara dinamis
            date.setMonth(date.getMonth() + duration);
            
            // Konversi format ke YYYY-MM-DD agar dibaca oleh tag input date HTML5
            let y = date.getFullYear();
            let m = String(date.getMonth() + 1).padStart(2, '0');
            let d = String(date.getDate()).padStart(2, '0');
            
            document.getElementById('end_date').value = y + '-' + m + '-' + d;
        }
    }
</script>
@endsection