<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penempatan Prakerin</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 11px;}
        .status-diterima { color: green; font-weight: bold; }
        .status-tolak { color: red; font-style: italic; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        .ttd { margin-top: 60px; border-bottom: 1px solid #333; width: 200px; display: inline-block; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Rekomendasi Penempatan Prakerin</h1>
        <p>SMK Taruna Karya Mandiri</p>
        <p>Tahun Ajaran: {{ $selectedYear->name ?? 'Semua Periode' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NISN</th>
                <th width="25%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="10%">Skor SMART</th>
                <th width="25%">Penempatan Industri</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($placements as $index => $placement)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $placement->student->nisn }}</td>
                    <td>{{ $placement->student->name }}</td>
                    <td>{{ $placement->student->class }}</td>
                    <td style="text-align: center;"><b>{{ $placement->final_smart_score }}</b></td>
                    <td>{{ $placement->company->name ?? '-' }}</td>
                    <td>
                        @if($placement->company_id)
                            <span class="status-diterima">Diterima</span>
                        @else
                            <span class="status-tolak">Pembinaan</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data penempatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
        <p>Mengetahui,</p>
        <p>Kepala Hubin / Penanggung Jawab Prakerin</p>
        <div class="ttd"></div>
        <p>(...........................................................)</p>
    </div>

</body>
</html>