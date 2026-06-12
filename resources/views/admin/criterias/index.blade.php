@extends('layouts.hubin')

@section('title', 'Manajemen Bobot Kriteria')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center gap-2">
            <span class="text-xl">✅</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center gap-2">
            <span class="text-xl">⚠️</span> {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Konfigurasi Pembobotan Kriteria SMART</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah nilai koefisien kepentingan (bobot) kriteria secara dinamis. Total akumulasi seluruh bobot harus bernilai 1.00.</p>
    </div>

    <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-xl shadow-sm mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-sm">
        <div class="flex items-center gap-2 text-indigo-900 font-bold">
            <span class="text-lg">💡</span> Aturan Normalisasi Bobot Metode SMART: 
        </div>
        <div class="bg-white px-3 py-1.5 rounded-lg border border-indigo-100 font-mono text-xs text-indigo-600 shadow-inner">
            W1 + W2 + W3 + W4 + W5 = 1.00
        </div>
    </div>

    <form action="{{ route('admin.criterias.update') }}" method="POST" class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 mb-10 p-6">
        @csrf
        @method('PUT')

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase tracking-wider text-xs">Kode Kriteria</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-500 uppercase tracking-wider text-xs">Nama Kriteria</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Sifat</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Nilai Bobot (Skor 0.00 - 1.00)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($criterias as $crit)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-indigo-600 uppercase">{{ $crit->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $crit->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-1 text-xs font-extrabold rounded-full border {{ $crit->type == 'benefit' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                    {{ strtoupper($crit->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" step="0.01" min="0.00" max="1.00" name="weights[{{ $crit->id }}]" value="{{ old('weights.' . $crit->id, $crit->weight) }}" class="font-mono font-extrabold text-center w-32 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-3 mt-6 border-t pt-4 border-gray-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow transition duration-150 text-sm">
                Simpan Perubahan Bobot
            </button>
        </div>
    </form>

@endsection