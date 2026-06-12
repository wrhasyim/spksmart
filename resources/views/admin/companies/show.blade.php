@extends('layouts.hubin')

@section('title', 'Kelola Kuota Perusahaan')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:underline font-medium">&larr; Kembali ke Daftar Perusahaan</a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gradient-to-r from-gray-50 to-white">
        <div class="md:col-span-2">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">Profil Industri Mitra</span>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $company->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">📍 {{ $company->address ?? 'Alamat belum diisi.' }}</p>
        </div>
        <div class="border-t md:border-t-0 md:border-l border-gray-200 pt-4 md:pt-0 md:pl-6 text-sm text-gray-600 flex flex-col justify-center gap-1">
            <div><b>📞 Telepon:</b> {{ $company->phone ?? '-' }}</div>
            <div><b>✉️ Email:</b> {{ $company->email ?? '-' }}</div>
        </div>
    </div>

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <label for="academicYearSelect" class="text-sm font-bold text-gray-700">Atur Kuota Pada Periode:</label>
            <select name="academic_year_id" id="academicYearSelect" onchange="document.getElementById('filterForm').submit()" class="mt-1 block py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm cursor-pointer font-semibold">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} @if($year->is_active) • (Aktif) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-xs text-gray-500 bg-indigo-50 text-indigo-700 p-2 rounded-md border border-indigo-100">
            *Lowongan industri dan batasan nilai bersifat dinamis & terikat pada tahun periode di atas.
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-extrabold text-gray-800 text-lg">Daftar Gelombang Lowongan / Kriteria Kuota</h3>
                <p class="text-xs text-gray-500 mt-0.5">Kriteria mutlak seleksi masuk algoritma SMART untuk perusahaan ini.</p>
            </div>
            <a href="{{ route('admin.company_slots.create', ['company_id' => $company->id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-4 py-2.5 rounded-lg shadow-sm transition">
                + Buka Lowongan Gelombang Baru
            </a>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Nama Gelombang</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Jurusan Target</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs bg-gray-100/50">Status Kuota</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-600 uppercase tracking-wider text-xs">Passing Grade (Total / Absen)</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Masa Berlaku</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($slots as $slot)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-4 whitespace-nowrap font-bold text-gray-900">{{ $slot->batch_name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="bg-gray-100 text-gray-800 font-semibold text-xs px-2.5 py-1 rounded-md border border-gray-200">
                                    {{ $slot->major ? $slot->major->name : '-' }}
                                </span>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap bg-gray-50/50 text-center">
                                <div class="flex items-center justify-center gap-1.5 text-xs">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold" title="Kuota Total">
                                        Kuota: {{ $slot->quota }}
                                    </span>
                                    <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded font-bold" title="Kuota Terisi">
                                        Terisi: {{ $slot->kuota_terisi }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded font-extrabold {{ $slot->sisa_kuota > 0 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800' }}" title="Sisa Kuota">
                                        Sisa: {{ $slot->sisa_kuota }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center font-medium">
                                <div class="text-gray-900">Total Min: <span class="text-indigo-600 font-bold">{{ $slot->min_total_score }}</span></div>
                                <div class="text-xs text-gray-500 mt-0.5">Absen Min: {{ $slot->min_absensi_score }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500">
                                <div><b>Mulai:</b> {{ \Carbon\Carbon::parse($slot->start_date)->translatedFormat('d M Y') }}</div>
                                <div class="mt-0.5"><b>Selesai:</b> {{ \Carbon\Carbon::parse($slot->end_date)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right font-medium">
                                <div class="flex justify-end space-x-1.5 text-xs">
                                    <a href="{{ route('admin.company_slots.edit', $slot->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2.5 py-1 rounded border border-indigo-200">
                                        Ubah
                                    </a>
                                    <form action="{{ route('admin.company_slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus gelombang lowongan ini?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2.5 py-1 rounded border border-red-200">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                🏢 Belum ada gelombang lowongan kuota yang dibuka untuk perusahaan ini pada periode tahun ajaran ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection