<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Major;
use App\Models\Criterion;
use App\Models\Assessment;
use App\Models\AcademicYear;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Ambil tahun ajaran aktif saat ini
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            throw new Exception('Gagal Import: Tidak ada Tahun Ajaran yang aktif. Silakan set di menu Tahun Ajaran.');
        }

        // Ambil semua kriteria aktif untuk memetakan nama kolom di Excel
        $criteriaList = Criterion::all();

        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Lewati baris jika NISN kosong
                if (!isset($row['nisn']) || trim($row['nisn']) == '') continue;

                // Cari ID Jurusan berdasarkan KODE jurusan di Excel (Misal: baris 'kode_jurusan' isinya 'TKJ')
                $majorCode = trim($row['kode_jurusan']);
                $major = Major::where('code', $majorCode)->first();

                if (!$major) {
                    throw new Exception("Gagal Import: Kode Jurusan '{$majorCode}' pada siswa '{$row['nama_lengkap']}' tidak ditemukan di Master Jurusan.");
                }

                // 1. Simpan atau Update Biodata Siswa (Patokan: NISN)
                $student = Student::updateOrCreate(
                    ['nisn' => trim($row['nisn'])],
                    [
                        'name' => trim($row['nama_lengkap']),
                        'class_name' => trim($row['kelas']),
                        'major_id' => $major->id,
                        'gender' => strtoupper(trim($row['lp'])) === 'P' ? 'P' : 'L',
                        'parent_phone' => trim($row['no_wa_ortu']),
                        'academic_year_id' => $activeYear->id,
                        'status' => 'belum_prakerin', // Reset status saat diimport
                    ]
                );

                // 2. Siapkan array untuk menampung nilai assessment dinamis
                $assessmentData = [];
                foreach ($criteriaList as $criterion) {
                    // Laravel Excel (Dengan WithHeadingRow) otomatis mengubah header menjadi snake_case
                    // Misal header Excel "Nilai Absensi", key array-nya jadi "nilai_absensi"
                    $columnKey = 'nilai_' . strtolower(str_replace(' ', '_', $criterion->name));
                    
                    // Ambil nilai dari kolom tersebut, default 0 jika kosong
                    $assessmentData[$criterion->code] = isset($row[$columnKey]) ? (float) $row[$columnKey] : 0;
                }

                // 3. Simpan ke tabel Assessments (Bentuk JSON jika strukturnya dinamis, atau update tabel lama)
                // KARENA KITA MENGGUNAKAN KRITERIA DINAMIS, mari kita simpan dalam bentuk JSON agar kolom tidak perlu ditambah manual di database.
                
                // *CATATAN: Pastikan Anda telah mengupdate file migration create_assessments_table agar menggunakan kolom tipe JSON*
                Assessment::updateOrCreate(
                    ['student_id' => $student->id],
                    ['scores_data' => json_encode($assessmentData)]
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}