@extends('layouts.hubin')

@section('title', 'Kelola Tahun Ajaran')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-xl shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
            <ul class="list-disc pl-5 text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Tahun Ajaran
            </h2>
            <form action="{{ route('admin.academic_years.store') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Periode / Tahun</label>
                    <input type="text" name="name" placeholder="Contoh: 2026/2027 - Ganjil" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-200">
                    Simpan Periode
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-xl font-bold text-gray-900">Daftar Tahun Ajaran</h1>
                <p class="text-sm text-gray-500 mt-1">Sistem dan kalkulasi SPK akan merujuk ke periode yang berstatus "Aktif".</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <tbody class="divide-y divide-gray-100">
                        @forelse($academicYears as $year)
                            <tr class="hover:bg-indigo-50/30 transition duration-150">
                                <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $year->name }}
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-center">
                                    @if($year->is_active)
                                        <span class="px-4 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Sedang Aktif
                                        </span>
                                    @else
                                        <form action="{{ route('admin.academic_years.set_active', $year->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-xs bg-gray-100 hover:bg-indigo-100 hover:text-indigo-700 text-gray-700 font-bold py-1.5 px-4 rounded-full transition duration-150 border border-gray-200 hover:border-indigo-200">
                                                Jadikan Aktif
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-right">
                                    @if(!$year->is_active)
                                        <form action="{{ route('admin.academic_years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini? Data terkait di dalamnya akan ikut terhapus!')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition duration-150 flex items-center justify-end w-full">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak dapat dihapus</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                    Belum ada data tahun ajaran. Silakan buat entri baru pada form di samping.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection