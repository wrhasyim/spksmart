<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Master Data Siswa') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.students.sample-excel') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded shadow text-sm">
                    ↓ Download Template
                </a>
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded shadow text-sm">
                    ↑ Import Excel
                </button>
                <a href="{{ route('admin.students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                    + Tambah Manual
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">{{ session('error') }}</div>
            @endif
            @if (session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">{{ session('info') }}</div>
            @endif

            <div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Import Data Siswa</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500 mb-4">Pastikan format Excel sesuai dengan template yang diunduh. Kolom jurusan menggunakan Kode Jurusan.</p>
                            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="file_excel" accept=".xlsx, .xls" required class="mb-4 w-full border p-2 rounded">
                                <div class="flex justify-between mt-4">
                                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">Batal</button>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Upload & Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b-2 border-gray-200 text-sm">
                                <th class="p-3 font-semibold text-gray-700">NISN</th>
                                <th class="p-3 font-semibold text-gray-700">Nama Lengkap</th>
                                <th class="p-3 font-semibold text-gray-700">L/P</th>
                                <th class="p-3 font-semibold text-gray-700">Jurusan</th>
                                <th class="p-3 font-semibold text-gray-700">Status</th>
                                <th class="p-3 font-semibold text-gray-700 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse ($students as $student)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-medium text-gray-600">{{ $student->nisn }}</td>
                                    <td class="p-3 font-bold text-gray-800">
                                        {{ $student->name }}
                                        <div class="text-xs text-gray-500 font-normal">WA Ortu: {{ $student->parent_phone ?? '-' }}</div>
                                    </td>
                                    <td class="p-3">{{ $student->gender }}</td>
                                    <td class="p-3">
                                        <span class="bg-indigo-100 text-indigo-800 py-1 px-2 rounded text-xs font-bold">{{ $student->major->code ?? 'N/A' }}</span>
                                    </td>
                                    <td class="p-3">
                                        @if($student->status === 'belum_prakerin')
                                            <span class="bg-gray-100 text-gray-800 py-1 px-2 rounded text-xs">Belum SPK</span>
                                        @elseif($student->status === 'lolos_prakerin')
                                            <span class="bg-green-100 text-green-800 py-1 px-2 rounded text-xs font-bold">Lolos (Final)</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 py-1 px-2 rounded text-xs capitalize">{{ str_replace('_', ' ', $student->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center space-x-2">
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data siswa ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">
                                        Belum ada data siswa. Silakan import melalui Excel atau tambah manual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>