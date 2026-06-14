@extends('layouts.hubin')

@section('title', 'Intervensi Manual Penempatan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex justify-between items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-xl font-black text-gray-900">⚙️ Intervensi Manual Penempatan</h1>
            <p class="text-sm text-gray-500 mt-1">Paksa penempatan siswa secara manual mengabaikan rekomendasi SMART.</p>
        </div>
        <a href="{{ route('admin.placements.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-bold transition">
            Batal & Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-6">
        <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center text-2xl font-black">
            {{ substr($placement->student->name, 0, 1) }}
        </div>
        <div>
            <h2 class="text-xl font-black text-gray-900">{{ $placement->student->name }}</h2>
            <div class="flex items-center gap-3 mt-1 text-sm font-medium text-gray-500">
                <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $placement->student->nisn ?? 'NISN Kosong' }}</span>
                <span>{{ $placement->student->major->name }} ({{ $placement->student->major->code }})</span>
                <span>Gender: {{ $placement->student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            
            <div class="mt-3 text-xs">
                Status Saat Ini: 
                @if($placement->status_pencocokan === 'rekomendasi')
                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Rekomendasi Sistem</span>
                @elseif($placement->status_pencocokan === 'waiting_list')
                    <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Waiting List (Kuota Habis)</span>
                @else
                    <span class="font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded">Pembinaan (Gagal Nilai)</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-amber-200 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
        
        <form action="{{ route('admin.placements.update', $placement->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 text-sm text-amber-800 font-medium mb-6">
                <strong>Peringatan:</strong> Memilih perusahaan di bawah ini akan secara paksa menempatkan siswa tersebut, memotong kuota perusahaan, dan mengubah statusnya menjadi <strong>FINAL (DI-ACC)</strong> secara permanen.
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Perusahaan & Gelombang Tujuan <span class="text-red-500">*</span></label>
                <select name="company_slot_id" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-white font-medium">
                    <option value="">-- Pilih Gelombang Industri --</option>
                    @foreach($companySlots as $slot)
                        @php
                            // Ambil info sisa kuota (Bisa dipindah ke controller agar lebih bersih, ini contoh cepat)
                            $terisi = \App\Models\Placement::where('company_slot_id', $slot->id)->where('status_pencocokan', 'final')->count();
                            $sisa = $slot->quota - $terisi;
                            $isSelected = ($placement->company_slot_id === $slot->id) ? 'selected' : '';
                        @endphp
                        <option value="{{ $slot->id }}" {{ $isSelected }}>
                            {{ $slot->company->name }} - {{ $slot->batch_name }} (Sisa Kuota: {{ $sisa > 0 ? $sisa : 'Penuh' }})
                        </option>
                    @endforeach
                </select>
                @error('company_slot_id') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Intervensi (Opsional)</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Titipan Waka Kurikulum, Pengecualian Syarat Tinggi Badan..." class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50">{{ old('notes', $placement->notes) }}</textarea>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm">
                    ⚠️ Simpan Intervensi (Jadikan Final)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection