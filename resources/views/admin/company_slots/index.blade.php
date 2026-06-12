<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Gelombang Lowongan Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow sm:rounded-lg" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-6 flex justify-between items-center">
                <p class="text-gray-600">Kelola kuota, kriteria, dan jadwal penempatan siswa ke industri.</p>
                <a href="{{ route('admin.company_slots.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700 transition duration-150 ease-in-out font-semibold">
                    + Buka Gelombang Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan & Gelombang</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Syarat Khusus</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal & Durasi Prakerin</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($slots as $slot)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $slot->company->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $slot->batch_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800">
                                            {{ $slot->major->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <div>SMART: &ge; {{ $slot->min_total_score }}</div>
                                        <div>Absensi: &ge; {{ $slot->min_absensi_score }}</div>
                                        <div class="mt-1.5">
                                            @if($slot->gender_requirement == 'Semua' || !$slot->gender_requirement)
                                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded bg-gray-100 text-gray-600 border border-gray-200">L/P (Semua)</span>
                                            @elseif($slot->gender_requirement == 'L')
                                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded bg-blue-50 text-blue-700 border border-blue-200">Khusus Laki-laki</span>
                                            @elseif($slot->gender_requirement == 'P')
                                                <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-bold rounded bg-pink-50 text-pink-700 border border-pink-200">Khusus Perempuan</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-black text-indigo-600">{{ $slot->quota }} Siswa</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @php
                                            $start = \Carbon\Carbon::parse($slot->start_date);
                                            $end = \Carbon\Carbon::parse($slot->end_date);
                                            $durasi = $start->diffInMonths($end);
                                            if($durasi == 0) $durasi = 1;
                                        @endphp
                                        <div class="font-extrabold text-indigo-700 mb-1">⏳ {{ $durasi }} Bulan</div>
                                        <div class="font-medium text-gray-900">🛫 Pemberangkatan: {{ $start->translatedFormat('d M Y') }}</div>
                                        <div class="text-red-600 font-medium">🛬 Penarikan: {{ $end->translatedFormat('d M Y') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data gelombang lowongan. Silakan tambah baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-app-layout>