@extends('layouts.hubin')

@section('title', 'Riwayat Penempatan')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">📜 Riwayat Penempatan Prakerin</h1>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Industri</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Skor SMART</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($history as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->academicYear->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $item->student->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->company ? $item->company->name : 'Pembinaan' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->final_smart_score }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $history->links() }}
        </div>
    </div>
</div>
@endsection