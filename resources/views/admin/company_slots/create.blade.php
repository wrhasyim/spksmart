<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buka Gelombang Lowongan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Maaf, data gagal disimpan karena alasan berikut:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.company_slots.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perusahaan Mitra Industri</label>
                            <select name="company_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Perusahaan --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jurusan Siswa yang Dibutuhkan</label>
                            <select name="major_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach($majors as $major)
                                    <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                        {{ $major->name }} ({{ $major->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Gelombang / Batch Lowongan</label>
                            <input type="text" name="batch_name" value="{{ old('batch_name') }}" placeholder="Contoh: Gelombang 1 - Utama" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kuota Penerimaan Siswa</label>
                            <input type="number" name="quota" value="{{ old('quota') }}" min="1" placeholder="Masukkan jumlah siswa" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Syarat Minimal Nilai Akhir (Skor SMART)</label>
                            <input type="number" step="0.01" name="min_total_score" value="{{ old('min_total_score', '0') }}" min="0" max="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">*Siswa dengan nilai kombinasi SMART di bawah angka ini otomatis tereliminasi.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Syarat Minimal Nilai Absensi Asli (0-100)</label>
                            <input type="number" name="min_absensi_score" value="{{ old('min_absensi_score', '0') }}" min="0" max="100" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-xs text-gray-500 mt-1">*Syarat mutlak kehadiran siswa di sekolah sebelum diterjunkan.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai Pelaksanaan (Berangkat)</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durasi Prakerin (Bulan)</label>
                            <select name="duration_months" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('duration_months') == 1 ? 'selected' : '' }}>1 Bulan</option>
                                <option value="2" {{ old('duration_months') == 2 ? 'selected' : '' }}>2 Bulan</option>
                                <option value="3" {{ old('duration_months') == 3 ? 'selected' : (old('duration_months') == null ? 'selected' : '') }}>3 Bulan (Standar)</option>
                                <option value="4" {{ old('duration_months') == 4 ? 'selected' : '' }}>4 Bulan</option>
                                <option value="5" {{ old('duration_months') == 5 ? 'selected' : '' }}>5 Bulan</option>
                                <option value="6" {{ old('duration_months') == 6 ? 'selected' : '' }}>6 Bulan</option>
                            </select>
                            <p class="text-xs text-indigo-600 mt-1 font-semibold">*Sistem Carbon otomatis menghitung tanggal penarikan siswa berdasarkan pilihan durasi ini.</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end items-center space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('admin.company_slots.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 font-semibold">
                            Simpan Gelombang Lowongan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>