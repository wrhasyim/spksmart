@extends('layouts.hubin')

@section('title', 'Manajemen Kriteria')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center gap-2 text-sm font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center gap-2 text-sm font-bold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Kriteria & Bobot SMART</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola parameter penilaian, tambah, ubah, atau hapus kriteria secara fleksibel.</p>
        </div>
        <a href="{{ route('admin.criterias.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow font-bold text-sm transition flex items-center gap-1.5">
            ➕ Tambah Kriteria Baru
        </a>
    </div>

    <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-sm">
        <div class="flex items-center gap-2 text-indigo-900 font-bold">
            <span class="text-lg">💡</span> Status Akumulasi Bobot Saat Ini: 
        </div>
        <div class="flex items-center gap-3">
            <span class="font-bold text-xs {{ abs($totalWeight - 1.00) <= 0.01 ? 'text-green-600' : 'text-red-600' }}">
                Total Bobot Terpakai: {{ number_format($totalWeight, 2) }} / 1.00
            </span>
            <div class="bg-white px-3 py-1.5 rounded-lg border border-indigo-100 font-mono text-xs text-indigo-600 shadow-inner">
                W1 + W2 + ... + Wn = 1.00
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase tracking-wider text-xs">Kode / Kolom DB</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase tracking-wider text-xs">Nama Kriteria</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Sifat</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Nilai Bobot</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($criterias as $crit)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-extrabold text-indigo-600 uppercase">{{ $crit->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $crit->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-full border {{ $crit->type == 'benefit' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                    {{ strtoupper($crit->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center font-mono font-bold">
                                {{ number_format($crit->weight, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold space-x-2">
                                <a href="{{ route('admin.criterias.edit', $crit->id) }}" class="text-indigo-600 hover:underline bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100">✏️ Edit</a>
                                
                                <form action="{{ route('admin.criterias.destroy', $crit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kriteria ini akan menyesuaikan perhitungan mesin SMART. Lanjutkan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline bg-red-50 px-3 py-1.5 rounded-lg border border-red-100">🗑️ Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-xs font-medium">Belum ada data kriteria. Silakan tambahkan kriteria baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection