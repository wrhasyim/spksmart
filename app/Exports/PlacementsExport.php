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
        return Placement::where('academic_year_id', $this->academicYearId)
            ->with(['student', 'company', 'student.major'])
            ->get();
    }

    /**
     * Header/Judul Kolom Excel
     */
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Skor Akhir SMART',
            'Penempatan Industri',
            'Status Penempatan',
        ];
    }

    /**
     * Pemetaan data ke dalam sel tabel Excel
     */
    public function map($placement): array
    {
        return [
            " " . $placement->student->nisn, // Spasi di awal agar NISN terbaca sebagai Teks (mencegah angka 0 di depan terpotong)
            $placement->student->name,
            $placement->student->class,
            $placement->student->major->code ?? '-',
            $placement->final_smart_score,
            $placement->company ? $placement->company->name : '- Program Pembinaan -',
            $placement->company_id ? 'Diterima' : 'Pembinaan',
        ];
    }

    /**
     * Styling / Pengaturan Tampilan Visual Excel
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // 1. Styling Header Baris 1 (Background Indigo, Font Putih Tebal)
                $sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                        'name' => 'Calibri',
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'], // Warna Indigo-600
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 2. Memberikan Border Tipis Abu-abu pada semua sel data
                if ($highestRow > 1) {
                    $sheet->getStyle('A1:G' . $highestRow)->applyFromArray([
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

                    // 3. Perataan Teks (Alignment) Kolom Tertentu
                    // Kolom NISN (A), Kelas (C), Jurusan (D), Skor (E), Status (G) -> Rata Tengah
                    $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C2:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    // Kolom Nama (B) dan Perusahaan (F) -> Rata Kiri
                    $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('F2:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}