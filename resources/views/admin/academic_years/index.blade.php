@extends('layouts.hubin')

@section('title', 'Manajemen Periode')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">📅 Periode Tahun Ajaran</h1>
            <p class="text-sm text-gray-500 mt-1">Buka periode baru dan tentukan tahun ajaran aktif untuk pengelompokan data SPK.</p>
        </div>
        <form action="{{ route('admin.academic_years.store') }}" method="POST" class="flex items-center gap-2 w-full md:w-auto">
            @csrf
            <input type="text" name="name" required placeholder="Cth: 2025/2026" class="rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2.5 bg-white w-full md:w-48 transition">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-md transition flex items-center gap-1 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Buka Periode
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-medium text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Kontrol</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($academicYears as $year)
                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            🎓 Tahun Ajaran {{ $year->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($year->is_active)
                                <span class="bg-green-100 border border-green-200 text-green-800 px-3 py-1 rounded-md text-xs font-black tracking-wide flex items-center w-fit mx-auto gap-1">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span> AKTIF BERJALAN
                                </span>
                            @else
                                <form action="{{ route('admin.academic_years.set_active', $year->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 text-gray-600 border border-gray-200 px-3 py-1 rounded-md text-xs font-bold transition shadow-sm">
                                        Aktifkan Periode Ini
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @if(!$year->is_active)
                            <form action="{{ route('admin.academic_years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Hapus periode ini beserta seluruh arsip datanya?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-gray-300 font-bold italic">Kunci Proteksi</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection