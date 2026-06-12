<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Assessment;
use App\Models\Major;
use App\Models\AcademicYear;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) return;

        foreach ($rows as $row) {
            // Abaikan baris jika NISN atau Nama kosong
            if (!isset($row['nisn']) || !isset($row['nama'])) continue;

            // 1. Cari atau buat jurusan otomatis berdasarkan Excel
            $majorCode = strtoupper(trim($row['kode_jurusan'] ?? 'TKJ'));
            $major = Major::firstOrCreate(
                ['code' => $majorCode],
                ['name' => $majorCode]
            );

            // 2. Simpan atau Update Data Siswa
            $student = Student::updateOrCreate(
                ['nisn' => trim($row['nisn'])], // Patokan update adalah NISN
                [
                    'name'             => trim($row['nama']),
                    'class'            => trim($row['kelas'] ?? '-'),
                    'gender'           => strtoupper(trim($row['jenis_kelamin'] ?? 'L')),
                    'major_id'         => $major->id,
                    'academic_year_id' => $activeYear->id,
                    'status'           => 'belum_prakerin',
                ]
            );

            // 3. Simpan atau Update Nilai Assessment (Jika kolom nilai diisi di Excel)
            if (isset($row['nilai_absensi'])) {
                Assessment::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'academic_year_id' => $activeYear->id,
                        'absensi'          => floatval($row['nilai_absensi'] ?? 0),
                        'fisik_mental'     => floatval($row['nilai_fisik'] ?? 0),
                        'keaktifan'        => floatval($row['nilai_keaktifan'] ?? 0),
                        'catatan_kasus'    => floatval($row['nilai_kasus'] ?? 0),
                        'administrasi'     => floatval($row['nilai_administrasi'] ?? 0),
                    ]
                );
            }
        }
    }
}