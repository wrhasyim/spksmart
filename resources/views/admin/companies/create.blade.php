@extends('layouts.hubin')

@section('title', 'Tambah Perusahaan Mitra')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center">
            <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Perusahaan
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-sm">
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-8 border border-gray-100">
            <div class="mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">Form Input Master Perusahaan</h2>
                <p class="text-sm text-gray-500 mt-1">Tambahkan data profil industri. Untuk pengaturan kuota dan syarat nilai, lakukan di menu Gelombang Lowongan.</p>
            </div>

            <form action="{{ route('admin.companies.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Perusahaan / Industri <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Toyota Motor Manufacturing" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Telepon (Opsional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 021-1234567" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Utama (Opsional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: hrd@toyota.co.id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Lengkap Perusahaan</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap industri..." class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg font-bold transition duration-200 w-full md:w-auto">
                        Simpan Data Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection