@extends('layouts.hubin')

@section('title', 'Detail Perusahaan & Kuota')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Perusahaan
        </a>
        
        <a href="{{ route('admin.companies.edit', $company->id) }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-xl shadow-sm font-semibold transition duration-150 text-sm">
            Edit Profil Perusahaan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-100 mb-8">
        <div class="px-6 py-6 border-b border-gray-100 bg-indigo-50/30 flex items-start gap-4">
            <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h2>
                <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $company->phone ?? 'Belum ada telepon' }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $company->email ?? 'Belum ada email' }}</span>
                </div>
                <p class="mt-2 text-sm text-gray-500">{{ $company->address ?? 'Alamat belum diatur' }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Gelombang Lowongan / Kuota Prakerin</h3>
            <p class="text-sm text-gray-500 mt-1">Atur posisi, kuota, dan jurusan yang diterima oleh perusahaan ini.</p>
        </div>
        <div>
            <a href="{{ route('admin.company-slots.create', ['company_id' => $company->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md font-bold transition duration-200 flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Kuota Lowongan
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi / Bagian</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jurusan Diterima</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($company->slots as $slot)
                        <tr class="hover:bg-indigo-50/30 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $slot->academicYear->name ?? '-' }}
                                @if(isset($slot->academicYear) && $slot->academicYear->is_active)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-bold">
                                {{ $slot->position_name ?? 'Prakerin Umum' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="bg-indigo-100 text-indigo-800 font-bold px-3 py-1 rounded-full">{{ $slot->quota }} Siswa</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($slot->majors as $major)
                                        <span class="bg-gray-100 border border-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-semibold">{{ $major->abbreviation ?? $major->name }}</span>
                                    @empty
                                        <span class="text-red-500 text-xs italic">Belum ada jurusan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="{{ route('admin.company-slots.edit', $slot->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg transition duration-150 font-bold text-xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.company-slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus lowongan ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg transition duration-150 font-bold text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-sm text-gray-500 font-medium">Belum ada data lowongan/kuota untuk perusahaan ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection