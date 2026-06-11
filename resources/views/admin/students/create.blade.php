<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - SPK Prakerin</title>
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
            <h2 class="text-xl font-bold text-gray-900 mb-6">Form Input Siswa Baru</h2>

            <form action="{{ route('admin.students.store') }}" method="POST">
                @csrf
                <input type="hidden" name="academic_year_id" value="{{ $academicYears->first()->id ?? '' }}">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required placeholder="1234567890">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required placeholder="Budi Santoso">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" name="class" value="{{ old('class') }}" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required placeholder="Contoh: XII RPL 1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" @selected(old('gender') == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender') == 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                    <select name="major_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" @selected(old('major_id') == $major->id)>{{ $major->name }} ({{ $major->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded shadow hover:bg-indigo-700 font-bold">
                        Simpan Data Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>