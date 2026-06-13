<?php

namespace App\Exports;

use App\Models\Criterion;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleStudentsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $criteria;

    public function __construct()
    {
        // Ambil semua kriteria yang aktif (tidak kena soft delete)
        $this->criteria = Criterion::all();
    }

    public function headings(): array
    {
        // Kolom Statis (Biodata Siswa)
        $headings = [
            'NISN',
            'Nama Lengkap',
            'Kelas',
            'Kode Jurusan',
            'L/P',
            'No WA Ortu'
        ];

        // Tambahkan Kolom Kriteria (Dinamis)
        foreach ($this->criteria as $criterion) {
            // Misal kriteria absensi, akan jadi header: "Nilai Absensi"
            $headings[] = 'Nilai ' . $criterion->name; 
        }

        return $headings;
    }

    public function array(): array
    {
        // Baris Contoh (Dummy Data) agar admin paham cara isinya
        $exampleRow = [
            '0061234567',         // NISN
            'Ahmad Fulan',        // Nama
            'XII TKJ 1',          // Kelas
            'TKJ',                // Kode Jurusan (Sangat penting pakai KODE, bukan nama panjang)
            'L',                  // L/P
            '081234567890'        // WA Ortu
        ];

        // Isi nilai contoh (angka 0-100) untuk setiap kriteria dinamis
        foreach ($this->criteria as $criterion) {
            $exampleRow[] = '85'; // Contoh nilai
        }

        return [$exampleRow];
    }

    public function styles(Worksheet $sheet)
    {
        // Beri warna tebal pada baris pertama (Header)
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // NISN
            'B' => 25, // Nama
            'C' => 15, // Kelas
            'D' => 15, // Jurusan
            'E' => 10, // L/P
            'F' => 18, // WA
            // Kolom nilai akan menyesuaikan otomatis
        ];
    }
}