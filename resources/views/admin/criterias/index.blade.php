@extends('layouts.hubin')

@section('title', 'Master Data Kriteria')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
        Master Data Kriteria
    </h2>
    <a href="{{ route('admin.criterias.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition duration-200 shadow-md shadow-indigo-600/20 text-sm flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Tambah Kriteria
    </a>
</div>

@if (session('success'))
    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm flex items-center gap-3 text-emerald-800 font-medium">
        ✅ {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm flex items-center gap-3 text-red-800 font-medium">
        ❌ {{ session('error') }}
    </div>
@endif

<div class="mb-6 bg-blue-50 border border-blue-100 p-5 rounded-2xl flex items-center justify-between shadow-sm">
    <div>
        <h3 class="text-blue-800 font-bold text-lg">Total Bobot Kriteria Saat Ini</h3>
        <p class="text-blue-600 text-sm">Pastikan total bobot bernilai pas 100 untuk hasil SPK SMART yang valid.</p>
    </div>
    <div class="text-3xl font-extrabold {{ $criterias->sum('weight') == 100 ? 'text-green-600' : 'text-red-600' }}">
        {{ $criterias->sum('weight') }}
    </div>
</div>

<div class="bg-white overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-3xl border border-gray-100">
    <div class="p-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="p-4 rounded-tl-xl">Kode</th>
                    <th class="p-4">Nama Kriteria</th>
                    <th class="p-4">Tipe (Atribut)</th>
                    <th class="p-4">Bobot (%)</th>
                    <th class="p-4 text-center rounded-tr-xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse ($criterias as $criterion)
                    <tr class="border-b border-gray-50 hover:bg-indigo-50/30 transition duration-150">
                        <td class="p-4 font-bold text-indigo-600">{{ $criterion->code }}</td>
                        <td class="p-4 font-medium text-gray-900">{{ $criterion->name }}</td>
                        <td class="p-4">
                            @if($criterion->type === 'benefit')
                                <span class="bg-emerald-100 text-emerald-800 py-1 px-3 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                    <span>+</span> Benefit
                                </span>
                            @else
                                <span class="bg-rose-100 text-rose-800 py-1 px-3 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                    <span>-</span> Cost
                                </span>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-gray-800">{{ $criterion->weight }}</td>
                        <td class="p-4 text-center space-x-3">
                            <a href="{{ route('admin.criterias.edit', $criterion->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold transition">Edit</a>
                            <form action="{{ route('admin.criterias.destroy', $criterion->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kriteria ini? (Data nilai siswa untuk kriteria ini akan diabaikan saat import berikutnya)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-800 font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500 bg-gray-50/50 rounded-b-xl">
                            Belum ada data kriteria. Silakan tambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection