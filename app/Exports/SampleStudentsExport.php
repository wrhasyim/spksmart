<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleStudentsExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Contoh data tiruan (Sample) sebagai panduan pengisian
     */
    public function array(): array
    {
        return [
            [
                '1234567890',          // nisn
                'Ahmad Fauzi',          // nama
                'XII TKJ 1',            // kelas
                'L',                    // jenis_kelamin
                'TKJ',                  // kode_jurusan
                '95',                   // nilai_absensi
                '88',                   // nilai_fisik
                '90',                   // nilai_keaktifan
                '0',                    // nilai_kasus (Cost, semakin kecil semakin baik)
                '100'                   // nilai_administrasi
            ],
            [
                '0987654321',          // nisn
                'Siti Aminah',          // nama
                'XII RPL 2',            // kelas
                'P',                    // jenis_kelamin
                'RPL',                  // kode_jurusan
                '90',                   // nilai_absensi
                '85',                   // nilai_fisik
                '92',                   // nilai_keaktifan
                '5',                    // nilai_kasus
                '95'                    // nilai_administrasi
            ]
        ];
    }

    /**
     * Header kolom baris pertama (Harus huruf kecil semua & snake_case)
     */
    public function headings(): array
    {
        return [
            'nisn',
            'nama',
            'kelas',
            'jenis_kelamin',
            'kode_jurusan',
            'nilai_absensi',
            'nilai_fisik',
            'nilai_keaktifan',
            'nilai_kasus',
            'nilai_administrasi'
        ];
    }

    /**
     * Memberikan gaya/style warna pada header Excel agar terlihat rapi
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Baris 1 (Header) diberi font tebal, teks putih, background indigo
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4F46E5']
                ]
            ],
        ];
    }
}