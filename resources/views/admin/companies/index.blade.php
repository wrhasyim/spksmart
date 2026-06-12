@extends('layouts.hubin')

@section('title', 'Kelola Master Perusahaan')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-sm">
            <p class="font-bold">Gagal</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Master Perusahaan Mitra</h1>
            <p class="text-sm text-gray-600 mt-1">Daftar pusat seluruh industri atau instansi yang menjadi mitra sekolah.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.companies.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded shadow font-bold transition duration-150 flex items-center">
                + Tambah Perusahaan Baru
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-10 border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak (Telp/Email)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat Lengkap</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($companies as $company)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $company->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div><span class="font-medium text-gray-700">📞</span> {{ $company->phone ?? 'Tidak ada' }}</div>
                                <div class="mt-1"><span class="font-medium text-gray-700">✉️</span> {{ $company->email ?? 'Tidak ada' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $company->address ?? 'Alamat belum diatur' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.companies.edit', $company->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded border border-indigo-200 transition duration-150">
                                        Ubah
                                    </a>
                                    
                                    <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data perusahaan ini? \n\nCatatan: Jika perusahaan sudah pernah digunakan di Gelombang Lowongan, sistem akan menolak penghapusan.')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded border border-red-200 transition duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada data master perusahaan. Silakan klik tombol "Tambah Perusahaan Baru" untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator && $companies->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

@endsection