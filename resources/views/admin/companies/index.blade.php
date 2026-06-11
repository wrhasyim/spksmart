@extends('layouts.hubin')

@section('title', 'Kelola Perusahaan')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <label for="academicYearSelect" class="text-sm font-bold text-gray-700">Tinjau Data Periode:</label>
            <select name="academic_year_id" id="academicYearSelect" onchange="document.getElementById('filterForm').submit()" class="mt-1 block py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} @if($year->is_active) • (Periode Saat Ini) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-xs text-gray-500">
            *Mengubah pilihan di atas akan menampilkan data perusahaan yang terikat secara spesifik pada periode tersebut.
        </div>
    </form>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Data Perusahaan Mitra</h1>
            <p class="text-sm text-gray-600">Daftar industri / perusahaan yang bekerja sama beserta pantauan sisa kuota.</p>
        </div>
        <a href="{{ route('admin.companies.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow font-bold transition">
            + Tambah Perusahaan
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-10">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Perusahaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Kuota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Syarat Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passing Grade (Total)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($companies as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $company->name }}
                            <br><span class="text-xs text-gray-500">{{ $company->address }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="font-bold text-gray-800">Master: {{ $company->quota }} Siswa</div>
                            <div class="text-green-600 font-semibold text-xs mt-1">Terisi: {{ $company->placements_count ?? 0 }} Siswa</div>
                            <div class="text-indigo-600 font-semibold text-xs">Sisa Kuota: {{ max(0, $company->quota - ($company->placements_count ?? 0)) }} Siswa</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $company->major->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $company->gender_requirement }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $company->min_total_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-2">
                            <a href="{{ route('admin.companies.edit', $company->id) }}" class="text-indigo-600 hover:underline bg-indigo-50 px-3 py-1 rounded">
                                Ubah
                            </a>
                            
                            <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Hapus data perusahaan ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada data perusahaan pada periode tahun ajaran ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator && $companies->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

@endsection