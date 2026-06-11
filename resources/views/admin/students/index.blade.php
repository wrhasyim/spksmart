<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa - SPK Prakerin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-200 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex space-x-8">
                    <div class="font-bold text-xl text-gray-800 flex items-center">Panel Hubin</div>
                    <div class="hidden md:flex space-x-6 items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600">Rekomendasi SPK</a>
                        <a href="{{ route('admin.academic-years.index') }}" class="text-gray-600 hover:text-indigo-600">Tahun Ajaran</a>
                        <a href="{{ route('admin.companies.index') }}" class="text-gray-600 hover:text-indigo-600">Perusahaan Mitra</a>
                        <a href="{{ route('admin.students.index') }}" class="text-indigo-600 font-bold border-b-2 border-indigo-600 h-16 flex items-center">Data Siswa & Nilai</a>
                    </div>
                </div>
                <div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline font-semibold">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Data Siswa & Nilai</h1>
                <p class="text-sm text-gray-600">Input parameter penilaian kriteria metode SMART untuk setiap siswa.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow font-bold transition">
                + Tambah Siswa Baru
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelengkapan Nilai</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi Input / Hapus</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->name }} <span class="text-xs text-gray-400">({{ $student->gender }})</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->class }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->major->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($student->assessment)
                                    <span class="text-green-600 font-semibold text-xs bg-green-50 px-2 py-1 rounded-full">✔ Sudah Diinput</span>
                                @else
                                    <span class="text-red-600 font-semibold text-xs bg-red-50 px-2 py-1 rounded-full">❗ Belum Ada Nilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-2">
                                <a href="{{ route('admin.students.assessment.edit', $student->id) }}" class="text-indigo-600 hover:underline bg-indigo-50 px-3 py-1 rounded">
                                    {{ $student->assessment ? 'Ubah Nilai' : 'Input Nilai' }}
                                </a>
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada data siswa. Silakan tambah data siswa baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>