<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Penempatan Prakerin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 flex justify-end">
                <form action="{{ route('admin.spk.generate') }}" method="POST" onsubmit="return confirm('Proses ini akan mereset penempatan saat ini. Lanjutkan?');">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        Eksekusi Rekomendasi SPK (SMART)
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-sm font-semibold text-gray-700">
                                <th class="p-3 border-b">Nama Siswa</th>
                                <th class="p-3 border-b">Jurusan</th>
                                <th class="p-3 border-b">Skor SMART</th>
                                <th class="p-3 border-b">Ditempatkan Di</th>
                                <th class="p-3 border-b">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-gray-50 text-sm">
                                <td class="p-3 border-b">Budi Santoso</td>
                                <td class="p-3 border-b">RPL</td>
                                <td class="p-3 border-b font-bold text-green-600">88.50</td>
                                <td class="p-3 border-b">PT. Teknologi Maju</td>
                                <td class="p-3 border-b">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Lolos</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>