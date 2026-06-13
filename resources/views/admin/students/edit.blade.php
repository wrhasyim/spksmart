@extends('layouts.hubin')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">✏️ Edit Profil Siswa</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi identitas dasar peserta didik.</p>
        </div>
        <div>
            <a href="{{ route('admin.students.index') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-xl font-bold transition flex items-center text-sm gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Batal & Kembali
            </a>
        </div>
    </div>

    <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-100">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Nomor Induk Siswa Nasional">
                    @error('nisn') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap Siswa</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Cth: Ahmad Budi">
                    @error('name') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="gender" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                        <option value="L" {{ old('gender', $student->gender) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $student->gender) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                    @error('gender') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">No. HP Orang Tua / Wali</label>
                    <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Cth: 08123456789">
                    @error('parent_phone') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan (Kompetensi Keahlian)</label>
                    <select name="major_id" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ old('major_id', $student->major_id) == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('major_id') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kelas</label>
                    <input type="text" name="class_name" value="{{ old('class_name', $student->class_name) }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-3 bg-gray-50 focus:bg-white transition" placeholder="Cth: XI RPL 1">
                    @error('class_name') <p class="text-red-500 text-xs font-bold mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <input type="hidden" name="academic_year_id" value="{{ $student->academic_year_id }}">

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection