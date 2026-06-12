@extends('layouts.hubin')

@section('title', 'Rekomendasi Penempatan')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
            <span class="block sm:inline font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form id="filterForm" method="GET" action="{{ url()->current() }}" class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
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
            *Mengubah pilihan di atas akan menampilkan rekapitulasi yang terikat secara spesifik pada periode tersebut.
        </div>
    </form>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Dashboard Penempatan Prakerin</h1>
            <p class="text-sm text-gray-600">Tahun Ajaran Aktif: <span class="font-bold text-indigo-700">{{ $activeYear->name ?? 'Tidak Ditemukan' }}</span></p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.spk.print', ['academic_year_id' => $selectedYearId]) }}" target="_blank" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg shadow-sm font-bold transition flex items-center text-sm">
                📄 Cetak PDF Laporan
            </a>
            
            <form action="{{ route('admin.spk.generate') }}" method="POST" onsubmit="return confirm('Sistem akan menghitung ulang nilai seluruh siswa dan mencocokkannya secara otomatis dengan kuota perusahaan. Lanjutkan?')">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-sm font-bold transition flex items-center text-sm">
                    ⚡ Proses Algoritma SMART
                </button>
            </form>
        </div>
    </div>

    @if(isset($chartData) && count($chartData) > 0)
        <div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Statistik Persebaran Penempatan Siswa</h2>
            <div class="w-full mx-auto relative" style="height: 350px;">
                <canvas id="companyBarChart"></canvas>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 mb-10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NISN / Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas / Jurusan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Skor SMART</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penempatan Industri</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($placements as $placement)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $placement->student->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $placement->student->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-800">{{ $placement->student->class }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $placement->student->major->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1 rounded-md text-sm font-bold shadow-sm">
                                    {{ $placement->final_smart_score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm max-w-[200px]">
                                @if($placement->company)
                                    <div class="font-bold text-green-700 truncate">{{ $placement->company->name }}</div>
                                @else
                                    <span class="text-red-500 font-medium">- Program Pembinaan -</span>
                                @endif

                                @if($placement->placement_method === 'MANUAL_OVERRIDE')
                                    <div class="mt-1">
                                        <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded border border-amber-200">✋ Diubah Manual</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex flex-col gap-1.5 items-start">
                                    <div class="flex gap-2 items-center">
                                        @if($placement->company_id)
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded bg-green-100 text-green-800 border border-green-200">Diterima</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded bg-red-100 text-red-800 border border-red-200">Pembinaan</span>
                                        @endif
                                        
                                        <button type="button" onclick="openCalcModal({{ $placement->id }})" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                            🧮 Detail Hitungan
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-3 mt-0.5">
                                        @if($placement->company_id)
                                            <a href="{{ route('admin.spk.letter', $placement->id) }}" target="_blank" class="text-xs font-medium text-gray-500 hover:text-gray-900 hover:underline">
                                                📄 Surat Pengantar
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.placements.edit', $placement->id) }}" class="text-xs font-bold text-amber-600 hover:text-amber-800 hover:underline">
                                            ⚙️ Ubah Manual
                                        </a>
                                    </div>

                                    @if($placement->placement_method === 'MANUAL_OVERRIDE')
                                        <button type="button" onclick="openJustifikasiModal({{ $placement->id }})" class="text-[11px] font-bold text-red-600 hover:text-red-800 hover:underline bg-red-50 px-2 py-0.5 rounded border border-red-100 mt-1">
                                            👁️ Lihat Alasan Intervensi
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada data kalkulasi penempatan pada periode ini. <br> Silakan klik tombol <b>⚡ Proses Algoritma SMART</b>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($placements instanceof \Illuminate\Pagination\LengthAwarePaginator && $placements->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $placements->links() }}
            </div>
        @endif
    </div>


    @foreach($placements as $placement)
        
        @php
            $a = $placement->student->assessment;
            $c_absensi = $a ? $a->absensi : 0;
            $c_fisik   = $a ? $a->fisik_mental : 0;
            $c_aktif   = $a ? $a->keaktifan : 0;
            $c_kasus   = $a ? $a->catatan_kasus : 0;
            $c_admin   = $a ? $a->administrasi : 0;

            // Bobot Desimal Standar
            $w_absensi = 0.30; $w_fisik = 0.15; $w_aktif = 0.15; $w_kasus = 0.25; $w_admin = 0.15;

            // Perhitungan Utilitas (Skala Max 100, Min 0)
            $u_absensi = $c_absensi / 100;
            $u_fisik   = $c_fisik / 100;
            $u_aktif   = $c_aktif / 100;
            $u_kasus   = (100 - $c_kasus) / 100; // Sifat Cost (Makin kecil makin baik)
            $u_admin   = $c_admin / 100;

            // Hasil Akhir Kriteria
            $h_absensi = $u_absensi * $w_absensi;
            $h_fisik   = $u_fisik * $w_fisik;
            $h_aktif   = $u_aktif * $w_aktif;
            $h_kasus   = $u_kasus * $w_kasus;
            $h_admin   = $u_admin * $w_admin;
            
            $total_skor = ($h_absensi + $h_fisik + $h_aktif + $h_kasus + $h_admin) * 100;
        @endphp

        <div id="calc-modal-{{ $placement->id }}" class="fixed inset-0 bg-gray-900/60 hidden flex items-center justify-center z-50 p-4 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full border-t-4 border-indigo-600 flex flex-col max-h-[85vh]">
                <div class="p-5 border-b border-gray-100 flex justify-between items-start flex-shrink-0">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">🧮 Transparansi Hitungan SMART</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Siswa: <b class="text-gray-800">{{ $placement->student->name }}</b> ({{ $placement->student->nisn }})</p>
                    </div>
                    <button type="button" onclick="closeCalcModal({{ $placement->id }})" class="text-gray-400 hover:text-red-500 font-bold text-xl transition">&times;</button>
                </div>
                
                <div class="p-5 overflow-y-auto flex-1 text-xs text-gray-700 space-y-4">
                    
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 text-indigo-900 mb-4">
                        <div class="font-bold text-sm mb-3">📐 Rumus Nilai Utilitas SMART:</div>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span>• <b>Benefit</b> (Absen, Fisik, Aktif, Admin) : <i>U<sub>i</sub></i> = </span>
                            <div class="inline-flex flex-col items-center text-xs font-bold leading-tight">
                                <span class="border-b border-indigo-400 pb-0.5 px-1">C<sub>out</sub> - 0</span>
                                <span class="pt-0.5 px-1">100 - 0</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-3">
                            <span>• <b>Cost</b> (Catatan Kasus) : <i>U<sub>i</sub></i> = </span>
                            <div class="inline-flex flex-col items-center text-xs font-bold leading-tight">
                                <span class="border-b border-indigo-400 pb-0.5 px-1">100 - C<sub>out</sub></span>
                                <span class="pt-0.5 px-1">100 - 0</span>
                            </div>
                        </div>

                        <div class="text-[11px] text-indigo-700 font-medium pt-2 border-t border-indigo-200">
                            *Keterangan: Nilai Akhir = <b>&Sigma; (Utilitas &times; Bobot) &times; 100</b>
                        </div>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg overflow-hidden text-center mt-4">
    <thead class="bg-gray-100 font-bold text-gray-600 uppercase text-[10px]">
        <tr>
            <th class="px-3 py-2 text-left">Kriteria</th>
            <th class="px-3 py-2">Sifat</th>
            <th class="px-3 py-2">Bobot (W)</th>
            <th class="px-3 py-2">Nilai (C)</th>
            <th class="px-3 py-2">Utilitas (U)</th>
            <th class="px-3 py-2 bg-indigo-50/50">Hasil (U x W)</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 font-medium">
        <tr>
            <td class="px-3 py-2 text-left font-bold">Absensi</td>
            <td class="px-3 py-2 text-green-600 font-bold">Benefit</td>
            <td class="px-3 py-2">0.30</td>
            <td class="px-3 py-2 font-bold text-gray-900">{{ $c_absensi }}</td>
            <td class="px-3 py-2">{{ number_format($u_absensi, 2) }}</td>
            <td class="px-3 py-2 font-bold text-indigo-600">{{ number_format($h_absensi, 4) }}</td>
        </tr>
        <tr>
            <td class="px-3 py-2 text-left font-bold">Fisik & Mental</td>
            <td class="px-3 py-2 text-green-600 font-bold">Benefit</td>
            <td class="px-3 py-2">0.15</td>
            <td class="px-3 py-2 font-bold text-gray-900">{{ $c_fisik }}</td>
            <td class="px-3 py-2">{{ number_format($u_fisik, 2) }}</td>
            <td class="px-3 py-2 font-bold text-indigo-600">{{ number_format($h_fisik, 4) }}</td>
        </tr>
        <tr>
            <td class="px-3 py-2 text-left font-bold">Keaktifan</td>
            <td class="px-3 py-2 text-green-600 font-bold">Benefit</td>
            <td class="px-3 py-2">0.15</td>
            <td class="px-3 py-2 font-bold text-gray-900">{{ $c_aktif }}</td>
            <td class="px-3 py-2">{{ number_format($u_aktif, 2) }}</td>
            <td class="px-3 py-2 font-bold text-indigo-600">{{ number_format($h_aktif, 4) }}</td>
        </tr>
        <tr class="bg-red-50/40">
            <td class="px-3 py-2 text-left font-bold text-red-900">Catatan Kasus</td>
            <td class="px-3 py-2 text-red-600 font-bold">Cost</td>
            <td class="px-3 py-2">0.25</td>
            <td class="px-3 py-2 font-bold text-red-600">{{ $c_kasus }}</td>
            <td class="px-3 py-2 text-red-700">{{ number_format($u_kasus, 2) }}</td>
            <td class="px-3 py-2 font-bold text-indigo-600">{{ number_format($h_kasus, 4) }}</td>
        </tr>
        <tr>
            <td class="px-3 py-2 text-left font-bold">Administrasi</td>
            <td class="px-3 py-2 text-green-600 font-bold">Benefit</td>
            <td class="px-3 py-2">0.15</td>
            <td class="px-3 py-2 font-bold text-gray-900">{{ $c_admin }}</td>
            <td class="px-3 py-2">{{ number_format($u_admin, 2) }}</td>
            <td class="px-3 py-2 font-bold text-indigo-600">{{ number_format($h_admin, 4) }}</td>
        </tr>
    </tbody>
    <tfoot class="bg-indigo-50 font-bold text-indigo-900 text-sm">
        <tr>
            <td colspan="5" class="px-3 py-2.5 text-right font-extrabold">TOTAL SKOR AKHIR :</td>
            <td class="px-3 py-2.5 text-center text-base font-black text-indigo-700 bg-indigo-100/50 border border-indigo-200 rounded-b-lg">
                {{ round($total_skor, 2) }}
            </td>
        </tr>
    </tfoot>
</table>
                </div>
                
                <div class="p-4 border-t border-gray-100 flex justify-end flex-shrink-0 bg-gray-50 rounded-b-xl">
                    <button type="button" onclick="closeCalcModal({{ $placement->id }})" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg font-bold shadow-sm text-xs transition">Mengerti & Tutup</button>
                </div>
            </div>
        </div>


        @if($placement->placement_method === 'MANUAL_OVERRIDE')
            <div id="justifikasi-modal-{{ $placement->id }}" class="fixed inset-0 bg-gray-900/60 hidden flex items-center justify-center z-50 p-4 backdrop-blur-sm animate-fade-in">
                <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6 border-t-4 border-red-500">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">📄 Berita Acara Intervensi Hubin</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Siswa: <b>{{ $placement->student->name }}</b></p>
                        </div>
                        <button type="button" onclick="closeJustifikasiModal({{ $placement->id }})" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-lg border border-red-100 text-xs text-red-800 leading-relaxed font-medium shadow-inner">
                        {!! nl2br(e($placement->notes)) !!}
                    </div>
                    
                    <div class="mt-5 flex justify-end">
                        <button type="button" onclick="closeJustifikasiModal({{ $placement->id }})" class="bg-gray-800 hover:bg-gray-900 text-white font-bold text-xs px-4 py-2 rounded shadow-sm transition">Tutup Berita Acara</button>
                    </div>
                </div>
            </div>
        @endif

    @endforeach

    <script>
        function openCalcModal(id) { 
            document.getElementById('calc-modal-' + id).classList.remove('hidden'); 
        }
        function closeCalcModal(id) { 
            document.getElementById('calc-modal-' + id).classList.add('hidden'); 
        }
        
        function openJustifikasiModal(id) { 
            document.getElementById('justifikasi-modal-' + id).classList.remove('hidden'); 
        }
        function closeJustifikasiModal(id) { 
            document.getElementById('justifikasi-modal-' + id).classList.add('hidden'); 
        }
    </script>

    @if(isset($chartData) && count($chartData) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const labels = chartData.map(item => {
                let name = item.company ? item.company.name : 'Tidak Diketahui';
                return name.length > 20 ? name.substring(0, 20) + '...' : name;
            });
            const dataCounts = chartData.map(item => item.total);

            const ctx = document.getElementById('companyBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Siswa Ditempatkan',
                        data: dataCounts,
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderColor: 'rgba(67, 56, 202, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
    @endif
@endsection