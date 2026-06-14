<?php

namespace App\Exports;

use App\Models\Placement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PlacementsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $academicYearId;

    public function __construct($academicYearId)
    {
        $this->academicYearId = $academicYearId;
    }

    /**
     * Ambil data dari database berdasarkan periode aktif
     */
    public function collection()
    {
        // Mengambil seluruh data draft penempatan pada tahun ajaran terpilih
        return Placement::where('academic_year_id', $this->academicYearId)
            ->with(['student.major', 'company', 'companySlot'])
            ->orderBy('final_smart_score', 'desc')
            ->get();
    }

    /**
     * Header/Judul Kolom Excel (Murni Array PHP)
     */
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Gender',
            'Kelas',
            'Jurusan',
            'Skor Akhir SMART',
            'Status Pencocokan',
            'Metode ACC',
            'Penempatan Industri',
            'Gelombang / Batch',
            'Keterangan / Alasan Detail',
        ];
    }

    /**
     * Pemetaan data ke dalam sel tabel Excel secara langsung
     */
    public function map($placement): array
    {
        // Proteksi jika baris penempatan tidak memiliki relasi siswa (data yatim)
        if (!$placement->student) {
            return [
                '-', '-', '-', '-', '-',
                $placement->final_smart_score ?? 0,
                strtoupper($placement->status_pencocokan ?? 'DRAFT'),
                $placement->placement_method ?? 'SYSTEM',
                $placement->company->name ?? '-',
                $placement->companySlot->batch_name ?? '-',
                $placement->notes ?? '-'
            ];
        }

        // Olah data alasan sistem agar rapi dalam satu baris Excel
        $keterangan = '';
        if (in_array($placement->status_pencocokan, ['rekomendasi', 'final'])) {
            $keterangan = 'Memenuhi standar kualifikasi & kuota industri.';
        } else {
            $keterangan = $placement->notes ?? 'Tidak ada catatan khusus.';
            // Mengubah enter (\n) menjadi pembatas pipa (|) agar sel Excel tidak rusak ke bawah
            $keterangan = str_replace("\n", " | ", $keterangan); 
        }

        return [
            " " . ($placement->student->nisn ?? '-'), // Spasi mencegah angka 0 di depan NISN hilang
            $placement->student->name ?? '-',
            ($placement->student->gender ?? '-') === 'L' ? 'Laki-laki' : 'Perempuan',
            $placement->student->class ?? '-',
            $placement->student->major->code ?? '-',
            $placement->final_smart_score ?? 0,
            strtoupper($placement->status_pencocokan ?? 'BELUM DIPROSES'),
            $placement->placement_method ?? 'SYSTEM',
            $placement->company->name ?? '- Belum Ada -',
            $placement->companySlot->batch_name ?? '-',
            $keterangan,
        ];
    }

    /**
     * Styling / Pengaturan Tampilan Visual Excel via Spreadsheet API
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 1. Styling Header Baris 1 (Background Indigo, Font Putih Tebal)
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                        'name' => 'Calibri',
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'], // Warna Indigo-600 resmi panel kita
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 2. Memberikan Border Tipis Abu-abu pada semua sel data
                if ($highestRow > 1) {
                    $sheet->getStyle('A1:K' . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB'], // Gray-300
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // 3. Perataan Teks (Alignment) Kolom
                    // Tengah: NISN(A), Gender(C), Kelas(D), Jurusan(E), Skor(F), Status(G), Metode(H), Gelombang(J)
                    $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C2:H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('J2:J' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Kiri: Nama(B), Industri(I), Keterangan(K)
                    $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('I2:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('K2:K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('K2:K' . $highestRow)->getAlignment()->setWrapText(true); // Auto-wrap teks keterangan panjang
                }
            },
        ];
    }
}