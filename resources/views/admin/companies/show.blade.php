@extends('layouts.hubin')

@section('title', 'Detail Perusahaan & Slot')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">🏢 Detail Industri: {{ $company->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi perusahaan dan alokasi slot kuota berdasarkan tahun ajaran.</p>
        </div>
        <div>
            <a href="{{ route('admin.companies.index') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-xl font-bold transition flex items-center text-sm gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="bg-white p-8 shadow-sm rounded-2xl border border-gray-100 flex flex-col md:flex-row gap-8 items-start">
        <div class="p-4 bg-indigo-50 rounded-2xl flex-shrink-0">
            <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div class="space-y-4 flex-grow">
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Nama Perusahaan / Industri</h3>
                <p class="text-xl font-extrabold text-gray-900">{{ $company->name }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Nomor Kontak / Telepon</h3>
                    <p class="text-md font-bold text-gray-800 flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $company->phone ?: 'Tidak ada kontak' }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</h3>
                    <p class="text-md font-bold text-gray-800 flex items-start gap-2 mt-1">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $company->address ?: 'Alamat belum diatur' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <form method="GET" action="{{ route('admin.companies.show', $company->id) }}" class="flex items-center gap-3 w-full md:w-auto">
            <label class="font-bold text-gray-700 text-sm whitespace-nowrap">Filter Kuota Tahun:</label>
            <select name="academic_year_id" onchange="this.form.submit()" class="block w-full md:w-64 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-white font-bold transition">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} {{ $year->is_active ? '(Periode Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>

        <button onclick="document.getElementById('createSlotModal').classList.remove('hidden')" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white shadow-md px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center text-sm gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Buka Kuota Baru
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-medium text-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-xl shadow-sm font-medium text-sm animate-fade-in">
            <div class="flex items-center mb-2 font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Gagal Menyimpan Kuota:
            </div>
            <ul class="list-disc list-inside space-y-1 ml-2 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById('createSlotModal').classList.remove('hidden');
            });
        </script>
    @endif

    <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Gelombang & Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota & Gender</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Standar Nilai Min.</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Pelaksanaan</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($slots as $slot)
                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-extrabold text-gray-900 text-sm">{{ $slot->batch_name }}</span>
                            <br>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded mt-1 inline-block">
                                {{ $slot->major->name ?? 'Semua Jurusan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-extrabold text-lg text-gray-900">{{ $slot->quota }}</span> <span class="text-xs text-gray-500">Orang</span>
                            <br>
                            <span class="text-xs font-medium text-gray-600">Gender: <strong>{{ strtoupper($slot->gender_requirement) }}</strong></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-600">Skor Total: <strong class="text-gray-900">{{ $slot->min_total_score }}</strong></div>
                            <div class="text-xs text-gray-600 mt-1">Skor Absensi: <strong class="text-gray-900">{{ $slot->min_absensi_score }}</strong></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-medium text-gray-900">{{ \Carbon\Carbon::parse($slot->start_date)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">s/d</div>
                            <div class="text-xs font-medium text-gray-900">{{ \Carbon\Carbon::parse($slot->end_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.company_slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus alokasi kuota ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="font-medium">Belum ada kuota / slot yang dibuka untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="createSlotModal" class="hidden fixed inset-0 bg-gray-900/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-gray-100 overflow-hidden my-8 animate-fade-in">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Buka Kuota / Gelombang Baru</h3>
            <button onclick="document.getElementById('createSlotModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
        </div>
        
        <form action="{{ route('admin.company_slots.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="company_id" value="{{ $company->id }}">
            <input type="hidden" name="academic_year_id" value="{{ $selectedYearId }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Gelombang (Batch)</label>
                    <input type="text" name="batch_name" value="{{ old('batch_name') }}" required placeholder="Cth: Gelombang 1 - 2026" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Program Keahlian (Jurusan)</label>
                    <select name="major_id" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>{{ $major->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Persyaratan Gender</label>
                    <select name="gender_requirement" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                        <option value="Semua" {{ old('gender_requirement') == 'Semua' ? 'selected' : '' }}>Semua Gender (L & P)</option>
                        <option value="L" {{ old('gender_requirement') == 'L' ? 'selected' : '' }}>Hanya Laki-laki (L)</option>
                        <option value="P" {{ old('gender_requirement') == 'P' ? 'selected' : '' }}>Hanya Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-indigo-700 mb-2">Total Kuota (Orang)</label>
                    <input type="number" name="quota" required min="1" value="{{ old('quota') }}" placeholder="Cth: 10" class="block w-full rounded-xl border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-indigo-50 focus:bg-white transition font-bold text-indigo-900">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Skor Total SPK</label>
                    <input type="number" step="0.01" name="min_total_score" required min="0" value="{{ old('min_total_score', 0) }}" placeholder="Cth: 75.5" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Skor Kehadiran</label>
                    <input type="number" step="0.01" name="min_absensi_score" required min="0" value="{{ old('min_absensi_score', 0) }}" placeholder="Cth: 80" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai Prakerin</label>
                    <input type="date" id="input_start_date" name="start_date" required value="{{ old('start_date') }}" onchange="calculateEndDate()" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-700 mb-2">Durasi Prakerin (Bulan)</label>
                    <input type="number" id="input_duration" min="1" max="12" placeholder="Cth: 3" oninput="calculateEndDate()" class="block w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 bg-blue-50 focus:bg-white transition font-bold text-blue-900">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-500 mb-2">Tanggal Selesai (Dihitung Otomatis)</label>
                    <input type="date" id="input_end_date" name="end_date" required readonly value="{{ old('end_date') }}" class="block w-full rounded-xl border-gray-200 shadow-sm text-sm px-4 py-3 bg-gray-100 text-gray-500 cursor-not-allowed font-medium">
                </div>

            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('createSlotModal').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl transition text-sm">Batal</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition text-sm">Simpan Kuota</button>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateEndDate() {
        const startDateInput = document.getElementById('input_start_date').value;
        const durationInput = document.getElementById('input_duration').value;
        const endDateField = document.getElementById('input_end_date');

        if (startDateInput && durationInput) {
            // Buat objek tanggal dari input
            let date = new Date(startDateInput);
            
            // Tambahkan jumlah bulan sesuai input durasi
            date.setMonth(date.getMonth() + parseInt(durationInput));
            
            // Format kembali ke YYYY-MM-DD agar bisa masuk ke input type="date"
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            
            endDateField.value = `${year}-${month}-${day}`;
        } else {
            // Kosongkan jika salah satu parameter belum diisi
            endDateField.value = '';
        }
    }
</script>
@endsection