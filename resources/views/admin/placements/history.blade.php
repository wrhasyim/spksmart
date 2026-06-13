@extends('layouts.hubin')

@section('title', 'Riwayat Penempatan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 border-b border-gray-100 pb-4">
        <h1 class="text-2xl font-extrabold text-gray-900">📜 Riwayat Penempatan Prakerin</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar arsip seluruh penempatan siswa dari berbagai periode tahun ajaran.</p>
    </div>

    <div class="bg-white shadow-sm overflow-hidden rounded-2xl mb-10 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Industri / Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Skor SMART</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($history as $item)
                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $item->academicYear->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $item->student->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $item->student->nisn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                            @if($item->company)
                                <span class="font-bold text-green-700">{{ $item->company->name }}</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded bg-red-100 text-red-800 border border-red-200">
                                    Pembinaan Sekolah
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-sm font-bold shadow-sm">
                                {{ $item->final_smart_score }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <p class="mt-4 text-sm text-gray-500 font-medium">Belum ada riwayat penempatan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($history instanceof \Illuminate\Pagination\LengthAwarePaginator && $history->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $history->links() }}
            </div>
        @endif
    </div>
</div>
@endsection