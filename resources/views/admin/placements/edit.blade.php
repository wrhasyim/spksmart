@extends('layouts.hubin')

@section('title', 'Intervensi Manual Penempatan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-200">
    
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:underline font-medium">&larr; Batal & Kembali ke Dashboard</a>
    </div>

    <div class="flex items-center gap-3 mb-2">
        <span class="bg-red-100 text-red-700 p-2 rounded-lg text-xl">⚠️</span>
        <h1 class="text-2xl font-extrabold text-gray-900">Form Intervensi Kepala Hubin</h1>
    </div>
    <p class="text-sm text-red-600 mb-6 font-medium border-l-4 border-red-500 pl-3">
        Tindakan ini akan <b>menganulir (membatalkan) objektivitas rekomendasi mesin Algoritma SMART</b>. Gunakan hanya pada kasus khusus (Force Majeure) yang membutuhkan intuisi/kebijaksanaan manusia.
    </p>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc pl-5 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gray-50 border border-gray-200 p-5 rounded-xl mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="text-xs text-gray-500 font-bold uppercase mb-1">Identitas Siswa</div>
            <div class="text-lg font-extrabold text-gray-900">{{ $placement->student->name }}</div>
            <div class="text-sm text-gray-600 font-medium">{{ $placement->student->nisn }} • Kelas {{ $placement->student->class }}</div>
        </div>
        <div>
            <div class="text-xs text-gray-500 font-bold uppercase mb-1">Keputusan Awal Algoritma SMART</div>
            <div class="text-lg font-extrabold text-indigo-700">{{ $placement->company ? 'DITERIMA DI: ' . $placement->company->name : 'PROGRAM PEMBINAAN / GAGAL' }}</div>
            <div class="text-sm text-gray-600 font-medium">Skor Akhir: {{ $placement->final_smart_score }} / 100</div>
        </div>
    </div>

    <form action="{{ route('admin.placements.update', $placement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-amber-50 border border-amber-200 p-6 rounded-xl mb-6">
            <h3 class="font-bold text-amber-900 mb-4 text-lg border-b border-amber-200 pb-2">Detail Pemindahan Target</h3>
            
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-900 mb-2">Pindahkan Penempatan Ke <span class="text-red-500">*</span></label>
                <select name="company_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-medium text-gray-700">
                    <option value="" class="text-red-600 font-bold">🚫 CABUT PENEMPATAN (MASUK PROGRAM PEMBINAAN SEKOLAH)</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ (old('company_id', $placement->company_id) == $company->id) ? 'selected' : '' }}>
                            🏢 PINDAHKAN KE: {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-900 mb-2">Pilih Kategori Kasus (Alasan Sah Intervensi) <span class="text-red-500">*</span></label>
                <select name="kategori_kasus" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 font-medium text-gray-700" required>
                    <option value="" disabled selected>-- Pilih Kategori Kasus --</option>
                    <option value="Kesehatan/Fisik Mendadak" {{ old('kategori_kasus') == 'Kesehatan/Fisik Mendadak' ? 'selected' : '' }}>Sakit Darurat / Kendala Fisik Mendadak</option>
                    <option value="Pelanggaran Disiplin Berat" {{ old('kategori_kasus') == 'Pelanggaran Disiplin Berat' ? 'selected' : '' }}>Kasus Pelanggaran Tata Tertib Berat</option>
                    <option value="Permintaan Khusus Industri" {{ old('kategori_kasus') == 'Permintaan Khusus Industri' ? 'selected' : '' }}>Permintaan / Penolakan Sepihak dari Industri</option>
                    <option value="Kendala Darurat Keluarga/Domisili" {{ old('kategori_kasus') == 'Kendala Darurat Keluarga/Domisili' ? 'selected' : '' }}>Kendala Keluarga / Domisili Jauh</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-900 mb-2">Justifikasi / Intuisi Kepala Hubin <span class="text-red-500">*</span></label>
                <textarea name="detail_kronologi" rows="4" minlength="10" placeholder="Tuliskan alasan spesifik mengapa sistem harus diabaikan pada kasus ini..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm" required>{{ old('detail_kronologi') }}</textarea>
                <p class="text-xs text-amber-700 mt-1.5 font-medium">*Justifikasi ini akan dicatat dalam database sebagai rekam jejak (Berita Acara) intervensi sistem.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <button type="submit" onclick="return confirm('Anda yakin ingin mengambil alih wewenang sistem dan mengubah penempatan siswa ini?')" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-extrabold shadow-md transition duration-150 flex items-center gap-2 text-sm uppercase tracking-wide">
                <span>⚡ Eksekusi Intervensi</span>
            </button>
        </div>
    </form>
</div>
@endsection