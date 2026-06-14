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

class LolosPrakerinExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $academicYearId;

    public function __construct($academicYearId)
    {
        $this->academicYearId = $academicYearId;
    }

    /**
     * Ambil data dari database yang HANYA berstatus FINAL
     */
    public function collection()
    {
        return Placement::where('academic_year_id', $this->academicYearId)
            ->where('status_pencocokan', 'final') // Hanya ambil yang sudah ACC/Final
            ->with(['student.major', 'company', 'companySlot'])
            ->get()
            ->sortBy('company.name'); // Urutkan berdasarkan nama perusahaan
    }

    /**
     * Header Kolom Excel
     */
    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Gender',
            'Kelas',
            'Jurusan',
            'Skor Kualitas',
            'Penempatan Industri (Final)',
            'Gelombang / Batch',
            'Tanggal Penarikan',
        ];
    }

    /**
     * Pemetaan data ke dalam sel tabel Excel
     */
    public function map($placement): array
    {
        // Hitung Tanggal Penarikan (opsional jika gelombang punya data end_date)
        $endDate = '-';
        if ($placement->companySlot && $placement->companySlot->end_date) {
            $endDate = \Carbon\Carbon::parse($placement->companySlot->end_date)->translatedFormat('d F Y');
        }

        return [
            " " . ($placement->student->nisn ?? '-'),
            $placement->student->name ?? '-',
            ($placement->student->gender ?? '-') === 'L' ? 'Laki-laki' : 'Perempuan',
            $placement->student->class ?? '-',
            $placement->student->major->code ?? '-',
            $placement->final_smart_score ?? 0,
            $placement->company->name ?? '-',
            $placement->companySlot->batch_name ?? '-',
            $endDate,
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

                // 1. Styling Header Baris 1 (Background Emerald/Hijau Lolos)
                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                        'name' => 'Calibri',
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '059669'], // Warna Emerald-600
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 2. Memberikan Border Tipis Abu-abu pada semua sel data
                if ($highestRow > 1) {
                    $sheet->getStyle('A1:I' . $highestRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D1D5DB'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // 3. Perataan Teks (Alignment)
                    $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C2:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('H2:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    
                    $sheet->getStyle('B2:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $sheet->getStyle('G2:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }
            },
        ];
    }
}