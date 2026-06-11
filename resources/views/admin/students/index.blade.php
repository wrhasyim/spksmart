@extends('layouts.hubin')

@section('title', 'Kelola Siswa')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <label for="academicYearSelect" class="text-sm font-bold text-gray-700">Tinjau Data Periode:</label>
            <select name="academic_year_id" id="academicYearSelect" onchange="document.getElementById('filterForm').submit()" class="mt-1 block py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} @if($year->is_active) • (Periode Saat Ini) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-xs text-gray-500">
            *Mengubah pilihan di atas akan menampilkan data siswa yang terikat secara spesifik pada periode tersebut.
        </div>
    </form>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Data Siswa & Nilai</h1>
            <p class="text-sm text-gray-600">Input parameter penilaian kriteria metode SMART untuk setiap siswa.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow font-bold transition">
            + Tambah Siswa Baru
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-10">
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
                            Belum ada data siswa pada periode tahun ajaran ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection