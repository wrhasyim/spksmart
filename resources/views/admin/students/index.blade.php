@extends('layouts.hubin')

@section('title', 'Kelola Siswa')

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

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <label for="academicYearSelect" class="text-sm font-bold text-gray-700">Tinjau Data Periode:</label>
            <select name="academic_year_id" id="academicYearSelect" onchange="document.getElementById('filterForm').submit()" class="mt-1 block py-2 px-4 border border-gray-300 bg-gray-50 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-medium cursor-pointer transition">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} @if($year->is_active) • (Aktif) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-xs text-gray-500 bg-gray-50 px-3 py-2 rounded-md border border-gray-100">
            *Data tabel otomatis menyesuaikan periode terpilih.
        </div>
    </form>

    <div class="mb-4">
        <h1 class="text-2xl font-extrabold text-gray-900">Manajemen Data Siswa & Nilai</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data identitas dan parameter kriteria SMART siswa untuk proses penempatan industri.</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full xl:w-auto">
            <div class="bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm border border-blue-100 flex items-center gap-2">
                <span class="text-lg">💡</span> 
                <span>Format kolom Excel (Baris 1): <code class="bg-white px-2 py-0.5 rounded shadow-sm text-xs font-mono">nisn | nama | kelas | jenis_kelamin | kode_jurusan | nilai_absensi | nilai_fisik | nilai_keaktifan | nilai_kasus | nilai_administrasi</code></span>
            </div>
            
            <a href="{{ route('admin.students.sample-excel') }}" class="flex-shrink-0 bg-white hover:bg-gray-50 text-indigo-700 border border-indigo-200 px-4 py-2 rounded-lg font-bold shadow-sm transition duration-150 text-sm flex items-center gap-2">
                <span>📄</span> Unduh Template
            </a>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="flex w-full sm:w-auto items-center gap-2 bg-gray-50 p-1.5 border border-gray-200 rounded-lg">
                @csrf
                <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="text-sm w-full sm:w-48 text-gray-600 file:cursor-pointer file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-white file:text-gray-700 file:shadow-sm hover:file:bg-gray-100 transition">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md text-sm font-bold shadow-sm transition duration-150">
                    Import
                </button>
            </form>

            <a href="{{ route('admin.students.create') }}" class="w-full sm:w-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-bold transition duration-150 text-sm">
                + Tambah Manual
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100 mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NISN</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas / Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Nilai</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $student->nisn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">Gender: {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700 font-medium">{{ $student->class }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $student->major ? $student->major->code : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($student->assessment)
                                    <span class="inline-flex items-center gap-1 text-green-700 font-bold text-xs bg-green-100 border border-green-200 px-3 py-1 rounded-full shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-red-700 font-bold text-xs bg-red-50 border border-red-200 px-3 py-1 rounded-full shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <a href="{{ route('admin.students.assessment.edit', $student->id) }}" class="text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md border border-indigo-200 transition duration-150 font-semibold">
                                        {{ $student->assessment ? 'Edit Nilai' : '+ Input Nilai' }}
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data siswa ini secara permanen?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md border border-red-200 transition duration-150 font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <span class="text-4xl mb-3">📂</span>
                                    <p class="text-sm font-medium text-gray-900">Belum ada data siswa</p>
                                    <p class="text-xs mt-1">Silakan unduh template Excel, isi data, lalu Import untuk proses cepat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

@endsection