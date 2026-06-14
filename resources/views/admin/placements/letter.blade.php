<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Prakerin - {{ $placement->student->name }}</title>
    <style>
        body { 
            font-family: 'Times-Roman', serif; 
            font-size: 14px; 
            line-height: 1.5; 
            color: #000; 
            margin: 20px 40px; 
        }
        
        .kop-surat { 
            text-align: center; 
            border-bottom: 3px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .kop-surat h2, .kop-surat h3, .kop-surat p { margin: 0; }
        .kop-surat h2 { font-size: 18px; text-transform: uppercase; font-weight: bold; }
        .kop-surat h1 { font-size: 22px; text-transform: uppercase; font-weight: bold; margin: 0; }
        .kop-surat p { font-size: 11px; font-style: italic; color: #333; }
        
        .info-surat { margin-bottom: 30px; }
        .info-surat table { width: 100%; border: none; }
        .info-surat td { padding: 2px 0; vertical-align: top; }
        
        .content { text-align: justify; }
        
        .content-table { width: 85%; margin: 15px 0 15px 20px; border-collapse: collapse; }
        .content-table td { padding: 5px 0; border: none; }
        .content-table td.label { width: 30%; font-weight: normal; }
        
        .footer { margin-top: 50px; width: 100%; }
        .footer-table { width: 100%; text-align: center; }
        .ttd-space { height: 75px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>{{ $settings->school_header_line1 ?? 'PEMERINTAH PROVINSI JAWA BARAT' }}</h2>
        <h2>{{ $settings->school_header_line2 ?? 'DINAS PENDIDIKAN' }}</h2>
        <h1>{{ $settings->school_name ?? 'SMK NEGERI 1 SPK' }}</h1>
        <p>{{ $settings->school_address ?? 'Jl. Pendidikan No. 123, Alamat Sekolah, Kode Pos 12345' }}</p>
        <p>
            Telepon: {{ $settings->school_phone ?? '(022) 123456' }} | 
            Email: {{ $settings->school_email ?? 'info@sekolah.sch.id' }} | 
            Web: {{ $settings->school_website ?? 'www.sekolah.sch.id' }}
        </p>
    </div>

    <div class="info-surat">
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td width="48%">
                    {{ $settings->letter_number_prefix ?? '421.5/123/SMK-SPK/' }}@if($placement->academicYear){{ str_replace('/', '-', $placement->academicYear->name) }}@else{{ date('Y') }}@endif
                </td>
                <td width="35%" style="text-align: right;">
                    {{ $settings->school_city ?? 'Bandung' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>1 (Satu) Berkas</td>
                <td></td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td><b>{{ $settings->letter_subject ?? 'Permohonan Praktik Kerja Industri (Prakerin)' }}</b></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Yth. Pimpinan/HRD <b>{{ $placement->company->name ?? 'Nama Perusahaan' }}</b><br>
        {{ $placement->company->address ?? 'Alamat Perusahaan' }}</p>

        <p>Dengan hormat,</p>
        <p>
            {{ $settings->letter_opening ?? 'Dalam rangka pelaksanaan program Pendidikan Sistem Ganda (PSG) dan untuk meningkatkan kompetensi lulusan Sekolah Menengah Kejuruan (SMK), kami memohon kesediaan Bapak/Ibu untuk menerima siswa kami melaksanakan Praktik Kerja Industri (Prakerin) di instansi/perusahaan yang Bapak/Ibu pimpin.' }}
        </p>
        
        <p>
            {{ $settings->letter_body ?? 'Adapun siswa yang direkomendasikan berdasarkan hasil seleksi kualifikasi akademik penempatan sistem (SMART Engine) adalah sebagai berikut:' }}
        </p>

        <table class="content-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: <b>{{ $placement->student->name ?? '-' }}</b></td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: {{ $placement->student->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kelas / Jurusan</td>
                <td>: XII / {{ $placement->student->major->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Ajaran</td>
                <td>: {{ $placement->academicYear->name ?? '-' }}</td>
            </tr>
            @if($placement->companySlot)
            <tr>
                <td class="label">Gelombang Alokasi</td>
                <td>: {{ $placement->companySlot->batch_name }}</td>
            </tr>
            @endif
        </table>

        <p>
            {{ $settings->letter_closing ?? 'Demikian surat permohonan ini kami sampaikan. Besar harapan kami Bapak/Ibu dapat mengabulkan permohonan ini. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.' }}
        </p>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td width="50%"></td>
                <td width="50%">
                    <p>Kepala {{ $settings->school_name ?? 'SMK Negeri 1 SPK' }},</p>
                    <div class="ttd-space"></div>
                    <p>
                        <b><u>{{ $settings->signature_name ?? 'Dr. Nama Kepala Sekolah, M.Pd.' }}</u></b><br>
                        NIP. {{ $settings->signature_nip ?? '19700101 199802 1 001' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>