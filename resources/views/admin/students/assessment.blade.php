@extends('layouts.hubin')

@section('title', 'Input Nilai SMART')

@section('content')

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:underline font-medium">&larr; Kembali ke Daftar Siswa</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-sm">
                <ul class="list-disc pl-5 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Input Nilai Kriteria SMART</h2>
            <div class="bg-indigo-50 border-l-4 border-indigo-600 p-4 mb-6 rounded-r">
                <p class="text-sm text-indigo-900">
                    Siswa: <span class="font-bold text-lg">{{ $student->name }}</span><br>
                    NISN: {{ $student->nisn }} | Kelas: {{ $student->class }} | Jurusan: {{ $student->major->code }}
                </p>
            </div>

            <form action="{{ route('admin.students.assessment.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Absensi (0-100)</label>
                        <input type="number" name="absensi" value="{{ old('absensi', $student->assessment->absensi ?? 70) }}" min="0" max="100" class="block w-full rounded-md border-gray-300 px-3 py-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-500 mt-1">*Makin tinggi makin baik</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Fisik & Mental (0-100)</label>
                        <input type="number" name="fisik_mental" value="{{ old('fisik_mental', $student->assessment->fisik_mental ?? 70) }}" min="0" max="100" class="block w-full rounded-md border-gray-300 px-3 py-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-500 mt-1">*Makin tinggi makin baik</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Keaktifan (0-100)</label>
                        <input type="number" name="keaktifan" value="{{ old('keaktifan', $student->assessment->keaktifan ?? 70) }}" min="0" max="100" class="block w-full rounded-md border-gray-300 px-3 py-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-500 mt-1">*Makin tinggi makin baik</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nilai Administrasi (0-100)</label>
                        <input type="number" name="administrasi" value="{{ old('administrasi', $student->assessment->administrasi ?? 70) }}" min="0" max="100" class="block w-full rounded-md border-gray-300 px-3 py-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <p class="text-xs text-gray-500 mt-1">*Makin tinggi makin baik</p>
                    </div>

                    <div class="md:col-span-2 bg-red-50 p-4 rounded-md border border-red-200">
                        <label class="block text-sm font-bold text-red-800 mb-1">Catatan Kasus / Pelanggaran (0-100)</label>
                        <input type="number" name="catatan_kasus" value="{{ old('catatan_kasus', $student->assessment->catatan_kasus ?? 0) }}" min="0" max="100" class="block w-full rounded-md border-red-300 px-3 py-2 border shadow-sm focus:border-red-500 focus:ring-red-500 bg-white" required>
                        <p class="text-xs text-red-600 mt-1 font-medium">*Kriteria Cost: Semakin kecil angkanya (sedikit pelanggaran), nilainya akan dihitung semakin baik oleh mesin SMART.</p>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-6 mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-md shadow hover:bg-indigo-700 font-bold transition">
                        Simpan Penilaian Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection