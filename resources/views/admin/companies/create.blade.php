<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perusahaan - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased py-10">

    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.companies.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke Daftar Perusahaan</a>
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
            <h2 class="text-xl font-bold text-gray-900 mb-6">Form Input Perusahaan Mitra Baru</h2>

            <form action="{{ route('admin.companies.store') }}" method="POST">
                @csrf
                
                <input type="hidden" name="academic_year_id" value="{{ $academicYears->first()->id ?? '' }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                        <input type="text" name="name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kuota Siswa</label>
                        <input type="number" name="quota" min="1" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jurusan yang Dituju</label>
                        <select name="major_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}">{{ $major->name }} ({{ $major->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Syarat Gender</label>
                        <select name="gender_requirement" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                            <option value="ALL">Semua (L/P)</option>
                            <option value="L">Hanya Laki-laki</option>
                            <option value="P">Hanya Perempuan</option>
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-800 border-t pt-4 mt-6 mb-4">Kriteria Minimal Nilai SMART (Skala 0-100)</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Nilai Kelulusan Total</label>
                        <input type="number" name="min_total_score" value="70" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Minimal Absensi</label>
                        <input type="number" name="min_absensi_score" value="70" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Minimal Fisik & Mental</label>
                        <input type="number" name="min_fisik_score" value="0" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Minimal Keaktifan</label>
                        <input type="number" name="min_keaktifan_score" value="0" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Minimal Administrasi</label>
                        <input type="number" name="min_administrasi_score" value="0" min="0" max="100" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2" required>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 font-bold">
                        Simpan Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>