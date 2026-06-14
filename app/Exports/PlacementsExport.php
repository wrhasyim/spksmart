<?php

namespace App\Exports;

use App\Models\Placement;
use App\Models\AppSetting;
use App\Models\AcademicYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PlacementsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $academicYearId;
    protected $rowNumber = 0;

    public function __construct($academicYearId)
    {
        $this->academicYearId = $academicYearId;
    }

    public function collection()
    {
        // Ambil data penempatan dengan relasinya
        return Placement::where('academic_year_id', $this->academicYearId)
            ->with(['student.major', 'company', 'companySlot'])
            ->orderBy('final_smart_score', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Lengkap',
            'L/P',
            'Kelas & Jurusan',
            'Skor SMART',
            'Penempatan Industri',
            'Status',
            'Keterangan / Catatan'
        ];
    }

    public function map($placement): array
    {
        $this->rowNumber++;

        // Format Tampilan Nama Perusahaan
        $industri = $placement->company ? $placement->company->name : '-';
        if ($placement->companySlot) {
            $industri .= ' (' . $placement->companySlot->batch_name . ')';
        }

        // Format Tampilan Status
        $status = $placement->status_pencocokan;
        if ($status === 'final') $status = 'Final (Di-ACC)';
        elseif ($status === 'rekomendasi') $status = 'Menunggu ACC';
        elseif ($status === 'waiting_list') $status = 'Waiting List (Kehabisan Kuota)';
        else $status = 'Sistem Pembinaan';

        // Format Catatan (Penting untuk membedakan Manual vs Mesin)
        $catatan = '-';
        if ($placement->placement_method === 'MANUAL_OVERRIDE') {
            $catatan = $placement->notes ?? 'Intervensi Manual oleh Admin';
        } elseif ($placement->notes) {
            $catatan = $placement->notes;
        } else {
            $catatan = '✅ Lolos Validasi SMART Engine';
        }

        return [
            $this->rowNumber,
            $placement->student->nisn ?? '-',
            $placement->student->name ?? '-',
            $placement->student->gender === 'L' ? 'L' : 'P',
            ($placement->student->class_name ?? '-') . ' / ' . ($placement->student->major->code ?? '-'),
            number_format($placement->final_smart_score, 2),
            $industri,
            $status,
            $catatan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Ambil data dinamis untuk Kop Surat
                $setting = AppSetting::first();
                $academicYear = AcademicYear::find($this->academicYearId);
                $tahun = $academicYear ? $academicYear->name : '-';
                $namaSekolah = $setting->nama_sekolah ?? 'SMK NEGERI 1 SPK';
                
                // 1. Sisipkan 6 baris kosong di bagian paling atas untuk area Kop Surat
                $sheet->insertNewRowBefore(1, 6);
                
                // 2. Isi teks Kop Surat ke dalam baris yang baru dibuat
                $sheet->setCellValue('A1', $setting->instansi_atas ?? 'PEMERINTAH PROVINSI / DINAS PENDIDIKAN');
                $sheet->setCellValue('A2', strtoupper($namaSekolah));
                $sheet->setCellValue('A3', $setting->alamat_sekolah ?? 'Alamat Sekolah Belum Diatur');
                $sheet->setCellValue('A5', 'REKAPITULASI HASIL PENEMPATAN PRAKERIN (PKL)');
                $sheet->setCellValue('A6', 'TAHUN AJARAN: ' . strtoupper($tahun));

                // 3. Gabungkan (Merge) Cell dari kolom A sampai I agar judulnya melintang ke tengah
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');
                $sheet->mergeCells('A5:I5');
                $sheet->mergeCells('A6:I6');

                // 4. Styling Teks Kop Surat (Rata tengah, Bold, Ukuran Font)
                $sheet->getStyle('A1:A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);

                // Hitung total baris seluruhnya setelah data masuk
                $highestRow = $sheet->getHighestRow();

                // 5. Styling Header Tabel Data (Letaknya sekarang di baris 7)
                $sheet->getStyle('A7:I7')->applyFromArray([
                    'font' => [
                        'bold' => true, 
                        'color' => ['argb' => 'FFFFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FF4F46E5'] // Warna Biru/Indigo Khas Dasbor Kamu
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 6. Buat Garis Tabel (Borders) otomatis sampai ke baris paling bawah
                $sheet->getStyle('A7:I' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // 7. Khusus untuk Kolom Keterangan (I), batasi lebarnya agar tidak memanjang tak terbatas (Wrap Text)
                $sheet->getStyle('I8:I' . $highestRow)->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension('I')->setAutoSize(false);
                $sheet->getColumnDimension('I')->setWidth(40);
                
                // 8. Rata Tengah & Rata Kiri sesuai tipe datanya
                $sheet->getStyle('A7:I' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A8:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
                $sheet->getStyle('B8:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // NISN
                $sheet->getStyle('D8:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // L/P
                $sheet->getStyle('F8:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Skor
            },
        ];
    }
}