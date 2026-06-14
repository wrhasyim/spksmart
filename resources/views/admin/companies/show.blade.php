@extends('layouts.hubin')

@section('title', 'Daftar Gelombang Lowongan')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-4 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Daftar Gelombang Lowongan Prakerin</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kuota, kriteria, dan jadwal penempatan siswa ke industri <strong class="text-indigo-600">{{ $company->name }}</strong>.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.companies.index') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-xl font-bold transition flex items-center text-sm shadow-sm">
                Kembali
            </a>
            <button onclick="document.getElementById('createSlotModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-md px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center text-sm gap-2">
                + Buka Gelombang Baru
            </button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center gap-3 w-fit">
        <form method="GET" action="{{ route('admin.companies.show', $company->id) }}" class="flex items-center gap-3">
            <label class="font-bold text-gray-700 text-sm whitespace-nowrap">Filter Kuota Tahun:</label>
            <select name="academic_year_id" onchange="this.form.submit()" class="block w-64 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 bg-gray-50 font-bold transition">
                @foreach($allYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }} {{ $year->is_active ? '(Periode Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-xl shadow-sm font-medium text-sm flex items-center">
            ✅ <span class="ml-2">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perusahaan & Gelombang</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Syarat Khusus</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kuota</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal & Durasi Prakerin</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($slots as $slot)
                    <tr class="hover:bg-indigo-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-extrabold text-gray-900 text-sm">{{ $company->name }}</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">{{ $slot->batch_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                @forelse($slot->majors as $major)
                                    <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                                        {{ $major->code }}
                                    </span>
                                @empty
                                    <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">Tidak ada jurusan</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs font-medium text-gray-600 mb-0.5">SMART: &ge; <span class="font-bold text-gray-900">{{ $slot->min_total_score }}</span></div>
                            <div class="text-xs font-medium text-gray-600 mb-2">Absensi: &ge; <span class="font-bold text-gray-900">{{ $slot->min_absensi_score }}</span></div>
                            
                            @if($slot->gender_requirement === 'P')
                                <span class="text-[10px] font-black text-pink-700 bg-pink-50 border border-pink-200 px-2 py-0.5 rounded">Khusus Perempuan</span>
                            @elseif($slot->gender_requirement === 'L')
                                <span class="text-[10px] font-black text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded">Khusus Laki-laki</span>
                            @else
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded">Semua Gender</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-extrabold text-lg text-indigo-700">{{ $slot->quota }}</span> <span class="text-xs font-bold text-indigo-700">Siswa</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $start = \Carbon\Carbon::parse($slot->start_date);
                                $end = \Carbon\Carbon::parse($slot->end_date);
                                $diffMonths = $start->diffInMonths($end) ?: 1; // Minimal 1 bulan
                            @endphp
                            <div class="text-xs font-black text-indigo-700 flex items-center gap-1.5 mb-1.5">
                                ⏳ {{ $diffMonths }} Bulan
                            </div>
                            <div class="text-xs font-medium text-gray-800 flex items-center gap-1.5 mb-0.5">
                                🛫 Pemberangkatan: {{ $start->format('d M Y') }}
                            </div>
                            <div class="text-xs font-medium text-red-600 flex items-center gap-1.5">
                                🛬 Penarikan: {{ $end->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <form action="{{ route('admin.company_slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus alokasi kuota ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-2 rounded-lg hover:bg-red-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="font-bold text-gray-500">Belum ada kuota / slot untuk periode ini.</p>
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
                    <input type="text" name="batch_name" required placeholder="Cth: Gelombang 1 - 2026" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilih Jurusan yang Diterima (Bisa > 1)</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        @foreach($majors as $major)
                            <label class="flex items-center p-2 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-indigo-300 transition shadow-sm">
                                <input type="checkbox" name="major_ids[]" value="{{ $major->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                <span class="ml-2 text-xs font-black text-gray-700">{{ $major->code }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Persyaratan Gender</label>
                    <select name="gender_requirement" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                        <option value="Semua">Semua Gender (L & P)</option>
                        <option value="L">Hanya Laki-laki (L)</option>
                        <option value="P">Hanya Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-indigo-700 mb-2">Total Kuota (Siswa)</label>
                    <input type="number" name="quota" required min="1" placeholder="Cth: 10" class="block w-full rounded-xl border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-indigo-50 focus:bg-white transition font-bold text-indigo-900">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Skor Total SPK</label>
                    <input type="number" step="0.01" name="min_total_score" required min="0" value="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Minimal Skor Kehadiran</label>
                    <input type="number" step="0.01" name="min_absensi_score" required min="0" value="0" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pemberangkatan (Mulai)</label>
                    <input type="date" id="input_start_date" name="start_date" required onchange="calculateEndDate()" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3 bg-gray-50 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-blue-700 mb-2">Durasi (Bulan)</label>
                    <input type="number" id="input_duration" min="1" max="12" placeholder="Cth: 3" oninput="calculateEndDate()" class="block w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 bg-blue-50 focus:bg-white transition font-bold text-blue-900">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-red-600 mb-2">Penarikan (Selesai - Dihitung Otomatis)</label>
                    <input type="date" id="input_end_date" name="end_date" required readonly class="block w-full rounded-xl border-red-200 shadow-sm text-sm px-4 py-3 bg-red-50 text-red-600 cursor-not-allowed font-medium">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('createSlotModal').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-xl transition text-sm">Batal</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition text-sm">Simpan Gelombang</button>
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
            let date = new Date(startDateInput);
            date.setMonth(date.getMonth() + parseInt(durationInput));
            
            let year = date.getFullYear();
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            
            endDateField.value = `${year}-${month}-${day}`;
        } else {
            endDateField.value = '';
        }
    }
</script>
@endsection