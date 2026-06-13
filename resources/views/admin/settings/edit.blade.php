<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Aplikasi & Kop Surat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-bold border-b pb-2 mb-4">Identitas Sekolah (Untuk Kop PDF)</h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Logo Sekolah</label>
                                @if($setting->logo_path)
                                    <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-20 mb-2">
                                @endif
                                <input type="file" name="logo" class="border p-2 w-full rounded">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, Maks: 2MB.</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Instansi Atas (Opsional)</label>
                                <input type="text" name="instansi_atas" value="{{ old('instansi_atas', $setting->instansi_atas) }}" class="border p-2 w-full rounded focus:ring-indigo-500" placeholder="Contoh: PEMERINTAH PROVINSI JAWA BARAT">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Sekolah</label>
                                <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $setting->nama_sekolah) }}" required class="border p-2 w-full rounded focus:ring-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_sekolah" rows="2" class="border p-2 w-full rounded focus:ring-indigo-500">{{ old('alamat_sekolah', $setting->alamat_sekolah) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kontak (Telp / Email / Web)</label>
                                <input type="text" name="kontak_sekolah" value="{{ old('kontak_sekolah', $setting->kontak_sekolah) }}" class="border p-2 w-full rounded focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold border-b pb-2 mb-4">Data Kepala Sekolah & Surat Pengantar</h3>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kepala Sekolah</label>
                                <input type="text" name="nama_kepala_sekolah" value="{{ old('nama_kepala_sekolah', $setting->nama_kepala_sekolah) }}" class="border p-2 w-full rounded focus:ring-indigo-500" placeholder="Lengkap dengan gelar">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $setting->nip_kepala_sekolah) }}" class="border p-2 w-full rounded focus:ring-indigo-500">
                            </div>

                            <div class="mb-4 mt-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Teks Paragraf Pengantar Prakerin (PDF)</label>
                                <textarea name="teks_pengantar_surat" rows="5" class="border p-2 w-full rounded focus:ring-indigo-500">{{ old('teks_pengantar_surat', $setting->teks_pengantar_surat) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Kalimat pembuka yang akan dicetak di Surat Pengantar siswa yang lolos.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 border-t pt-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>