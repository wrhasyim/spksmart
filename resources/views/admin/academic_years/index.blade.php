@extends('layouts.hubin')

@section('title', 'Kelola Tahun Ajaran')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 h-fit">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Tahun Ajaran</h2>
            <form action="{{ route('admin.academic-years.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Periode / Tahun</label>
                    <input type="text" name="name" placeholder="Contoh: 2026/2027 - Ganjil" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 sm:text-sm" required>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition">
                    Simpan Periode Baru
                </button>
            </form>
        </div>

        <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Daftar Tahun Ajaran</h1>
                <p class="text-sm text-gray-600">Sistem dan kalkulasi SPK akan merujuk ke periode yang berstatus "Aktif".</p>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="divide-y divide-gray-200">
                    @forelse($academicYears as $year)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $year->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                @if($year->is_active)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ✔ Sedang Aktif
                                    </span>
                                @else
                                    <form action="{{ route('admin.academic-years.set-active', $year->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded transition">
                                            Jadikan Aktif
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                @if(!$year->is_active)
                                    <form action="{{ route('admin.academic-years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini? Data terkait di dalamnya akan ikut terhapus!')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
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

@endsection