@extends('layouts.hubin')

@section('title', 'Ubah Perusahaan')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow border border-gray-200">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Ubah Data Master Perusahaan</h1>
    <p class="text-sm text-gray-500 mb-6">Perbarui profil identitas industri. Pengaturan kuota dilakukan di menu Gelombang Lowongan.</p>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.companies.update', $company->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Nama Perusahaan / Industri <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700">Nomor Telepon (Opsional)</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700">Email Utama (Opsional)</label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700">Alamat Lengkap Perusahaan</label>
                <textarea name="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $company->address) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
            <a href="{{ route('admin.companies.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded font-medium transition duration-150">Batal</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded font-bold shadow transition duration-150">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection