@extends('layouts.hubin')

@section('title', 'Master Jurusan')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">🗂️ Kompetensi Keahlian (Jurusan)</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar program keahlian aktif untuk pemetaan kuota slot industri.</p>
        </div>
        <div>
            <button onclick="document.getElementById('createMajorModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md px-5 py-2.5 rounded-xl font-bold transition flex items-center text-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Jurusan
            </button>
        </div>
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kompetensi Keahlian</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($majors as $major)
                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold px-3 py-1 rounded-md text-xs tracking-wider">
                                {{ $major->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ $major->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jurusan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500 font-medium">
                            Belum ada program keahlian siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="createMajorModal" class="hidden fixed inset-0 bg-gray-900/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Tambah Program Keahlian</h3>
            <button onclick="document.getElementById('createMajorModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
        </div>
        <form action="{{ route('admin.majors.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kode Singkatan Jurusan</label>
                <input type="text" name="code" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Cth: RPL / TKJ">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Jurusan</label>
                <input type="text" name="name" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Cth: Rekayasa Perangkat Lunak">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('createMajorModal').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl transition text-sm">Batal</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection