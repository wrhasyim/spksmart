<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai SMART - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased py-10">

    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.students.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke Data Siswa</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Input Nilai Kriteria SMART</h2>
            <p class="text-sm text-gray-600 mb-6">Siswa: <span class="font-bold">{{ $student->name }}</span> (NISN: {{ $student->nisn }})</p>

            <form action="{{ route('admin.students.assessment.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nilai Absensi (Skala 0-100)</label>
                    <input type="number" name="absensi" value="{{ old('absensi', $student->assessment->absensi ?? 70) }}" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nilai Fisik & Mental (Skala 0-100)</label>
                    <input type="number" name="fisik_mental" value="{{ old('fisik_mental', $student->assessment->fisik_mental ?? 70) }}" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nilai Keaktifan (Skala 0-100)</label>
                    <input type="number" name="keaktifan" value="{{ old('keaktifan', $student->assessment->keaktifan ?? 70) }}" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Catatan Kasus / Pelanggaran (Skala 0-100) <em>*Makin kecil makin baik</em></label>
                    <input type="number" name="catatan_kasus" value="{{ old('catatan_kasus', $student->assessment->catatan_kasus ?? 10) }}" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Nilai Administrasi (Skala 0-100)</label>
                    <input type="number" name="administrasi" value="{{ old('administrasi', $student->assessment->administrasi ?? 70) }}" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 font-bold">
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>