<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Siswa Secara Manual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                
                <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">NISN Siswa</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" required maxlength="20"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition" placeholder="Contoh: 0061234567">
                            @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Siswa</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition" placeholder="Masukkan nama sesuai ijazah">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kelas</label>
                            <input type="text" name="class_name" value="{{ old('class_name') }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition" placeholder="Contoh: XII PPLG 1">
                            @error('class_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Kompetensi</label>
                            <select name="major_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-white">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                        {{ $major->code }} - {{ $major->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('major_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="gender" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-white">
                                <option value="">-- Pilih Gender --</option>
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                            @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. WhatsApp Orang Tua / Wali</label>
                            <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition" placeholder="Contoh: 081234567890">
                            <p class="text-xs text-gray-400 mt-1">Digunakan untuk notifikasi Click-to-Chat WA otomatis.</p>
                            @error('parent_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Ajaran Aktif</label>
                            <select name="academic_year_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-100 outline-none transition bg-white">
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                        {{ $year->name }} {{ $year->is_active ? '(Aktif Saat Ini)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-5 mt-6">
                        <a href="{{ route('admin.students.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-6 rounded-xl transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition text-sm shadow-md shadow-indigo-600/20">
                            Simpan Siswa
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>