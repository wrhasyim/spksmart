<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Prakerin</title>
    <style>
        /* UBAH FONT-FAMILY MENJADI TIMES-ROMAN */
        body { font-family: 'Times-Roman', serif; font-size: 14px; line-height: 1.5; color: #000; margin: 20px 40px; }
        
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2, .kop-surat h3, .kop-surat p { margin: 0; }
        .kop-surat h2 { font-size: 20px; text-transform: uppercase; }
        .kop-surat h3 { font-size: 16px; font-weight: normal; }
        .kop-surat p { font-size: 12px; }
        .info-surat { margin-bottom: 30px; }
        .info-surat table { width: 100%; border: none; }
        .info-surat td { padding: 2px 0; vertical-align: top; }
        .content { text-align: justify; }
        .content-table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        .content-table td { padding: 5px; border: 1px solid #000; }
        .content-table td.label { width: 30%; font-weight: bold; }
        .footer { margin-top: 50px; width: 100%; }
        .footer-table { width: 100%; text-align: center; }
        .ttd-space { height: 80px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>PEMERINTAH PROVINSI JAWA BARAT</h2>
        <h2>DINAS PENDIDIKAN</h2>
        <h3>SMK NEGERI 1 CONTOH</h3>
        <p>Jl. Pendidikan No. 123, Kota Contoh, Kode Pos 12345</p>
        <p>Telepon: (021) 1234567 | Email: info@smkn1contoh.sch.id | Web: www.smkn1contoh.sch.id</p>
    </div>

    <div class="info-surat">
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td width="48%">421.5/123/SMKN1/{{ date('Y') }}</td>
                <td width="35%" style="text-align: right;">Kota Contoh, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
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
                <td><b>Permohonan Praktik Kerja Industri (Prakerin)</b></td>
                <td></td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Yth. Pimpinan/HRD <b>{{ $placement->company->name }}</b><br>
        {{ $placement->company->address }}</p>

        <p>Dengan hormat,</p>
        <p>Dalam rangka pelaksanaan program Pendidikan Sistem Ganda (PSG) dan untuk meningkatkan kompetensi lulusan Sekolah Menengah Kejuruan (SMK), kami memohon kesediaan Bapak/Ibu untuk menerima siswa kami melaksanakan Praktik Kerja Industri (Prakerin) di instansi/perusahaan yang Bapak/Ibu pimpin.</p>
        
        <p>Adapun siswa yang direkomendasikan berdasarkan hasil seleksi akademik (Metode SMART) adalah sebagai berikut:</p>

        <table class="content-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: {{ $placement->student->name }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: {{ $placement->student->nisn }}</td>
            </tr>
            <tr>
                <td class="label">Kelas / Jurusan</td>
                <td>: {{ $placement->student->class }} / {{ $placement->student->major->name }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Ajaran</td>
                <td>: {{ $placement->academicYear->name }}</td>
            </tr>
        </table>

        <p>Demikian surat permohonan ini kami sampaikan. Besar harapan kami Bapak/Ibu dapat mengabulkan permohonan ini. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.</p>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td width="50%"></td>
                <td width="50%">
                    <p>Kepala SMK Negeri 1 Contoh,</p>
                    <div class="ttd-space"></div>
                    <p><b><u>Dr. Nama Kepala Sekolah, M.Pd.</u></b><br>NIP. 19700101 199802 1 001</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>