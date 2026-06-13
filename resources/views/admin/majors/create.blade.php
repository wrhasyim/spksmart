<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Jurusan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.majors.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kode Jurusan (Singkatan)</label>
                        <input type="text" name="code" value="{{ old('code') }}" required class="border p-2 w-full rounded focus:ring-indigo-500" placeholder="Contoh: TKJ, RPL, TKR">
                        @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap Jurusan</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="border p-2 w-full rounded focus:ring-indigo-500" placeholder="Contoh: Teknik Komputer dan Jaringan">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.majors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>