@extends('layouts.hubin')

@section('title', 'Rekomendasi Penempatan')

@section('content')

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
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
            *Mengubah pilihan di atas akan menampilkan data siswa, perusahaan, dan rekapitulasi yang terikat secara spesifik pada periode tersebut.
        </div>
    </form>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rekomendasi Penempatan Prakerin</h1>
            <p class="text-sm text-gray-600">Tahun Ajaran Aktif: 
                <span class="font-semibold">{{ $activeYear->name ?? 'Tidak Ditemukan' }}</span>
            </p>
        </div>
        
        <form action="{{ route('admin.spk.generate') }}" method="POST" onsubmit="return confirm('Menjalankan kalkulasi akan mereset data penempatan sebelumnya. Lanjutkan?')">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-bold transition">
                🔄 Proses Rekomendasi SMART
            </button>
        </form>
    </div>

    @if(isset($chartData) && count($chartData) > 0)
        <div class="bg-white p-6 rounded-lg shadow mb-8 border border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Statistik Penempatan Siswa per Perusahaan</h2>
            <div class="w-full mx-auto" style="max-height: 350px;">
                <canvas id="companyBarChart"></canvas>
            </div>
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-10">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor SMART</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penempatan Industri</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($placements as $placement)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $placement->student->nisn }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $placement->student->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $placement->student->class }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $placement->student->major->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $placement->final_smart_score }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $placement->company->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($placement->company_id)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Diterima
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Program Pembinaan
                                </span>
                                <p class="text-xs text-gray-400 mt-1">{{ $placement->notes }}</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                            Belum ada data kalkulasi pada periode ini. Silakan klik tombol <i>"Proses Rekomendasi SMART"</i>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($chartData) && count($chartData) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            
            const labels = chartData.map(item => item.company ? item.company.name : 'Tidak Diketahui');
            const dataCounts = chartData.map(item => item.total);

            const ctx = document.getElementById('companyBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Siswa Ditempatkan',
                        data: dataCounts,
                        backgroundColor: 'rgba(79, 70, 229, 0.7)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { family: 'sans-serif' }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            ticks: { font: { family: 'sans-serif' } },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endif

@endsection