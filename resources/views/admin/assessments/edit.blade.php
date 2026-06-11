<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Input Nilai Kriteria: {{ $student->name }} ({{ $student->nisn }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('admin.assessments.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Absensi (Benefit)</label>
                            <input type="number" name="absensi" value="{{ old('absensi', $assessment->absensi) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <p class="text-xs text-gray-500 mt-1">Skala 0-100. Semakin tinggi semakin baik.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fisik & Mental (Benefit)</label>
                            <input type="number" name="fisik_mental" value="{{ old('fisik_mental', $assessment->fisik_mental) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keaktifan Sekolah (Benefit)</label>
                            <input type="number" name="keaktifan" value="{{ old('keaktifan', $assessment->keaktifan) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-red-600 font-bold">Catatan Kasus (Cost)</label>
                            <input type="number" name="catatan_kasus" value="{{ old('catatan_kasus', $assessment->catatan_kasus) }}" 
                                   class="mt-1 block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            <p class="text-xs text-red-500 mt-1">Skala 0-100. Semakin tinggi nilainya, skor SPK akan semakin TAMPIL BURUK.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Administrasi (Benefit)</label>
                            <input type="number" name="administrasi" value="{{ old('administrasi', $assessment->administrasi) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                            Simpan Penilaian
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>