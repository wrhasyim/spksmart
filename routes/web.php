<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CompanySlotController;

// Halaman utama publik
Route::get('/', function () {
    return view('welcome');
});

// Rute Autentikasi (Login/Logout menggunakan Username)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang diamankan (Wajib Login Hubin)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard utama penempatan SPK
    Route::get('/dashboard', [SpkController::class, 'index'])->name('dashboard');
    Route::post('/spk/generate', [SpkController::class, 'generate'])->name('admin.spk.generate');
    
    // 👇 TAMBAHKAN BARIS INI UNTUK CETAK PDF 👇
    Route::get('/spk/print-pdf', [SpkController::class, 'printPdf'])->name('admin.spk.print');
    // 👇 TAMBAHKAN BARIS INI 👇
    Route::get('/spk/placement/{placement}/letter', [SpkController::class, 'printLetter'])->name('admin.spk.letter');
    
    // Rute untuk Manajemen Slot/Gelombang Perusahaan (DIPERBAIKI PENAMAANNYA)
    Route::resource('company_slots', CompanySlotController::class)->names('admin.company_slots');

    // Manajemen Profil (Opsional, jika masih digunakan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Manajemen Perusahaan Mitra Industri
    Route::get('/companies', [CompanyController::class, 'index'])->name('admin.companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('admin.companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('admin.companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('admin.companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('admin.companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('admin.companies.destroy');

    // Manajemen Tahun Ajaran
    Route::get('/academic-years', [AcademicYearController::class, 'index'])->name('admin.academic-years.index');
    Route::post('/academic-years', [AcademicYearController::class, 'store'])->name('admin.academic-years.store');
    Route::post('/academic-years/{academic_year}/set-active', [AcademicYearController::class, 'setActive'])->name('admin.academic-years.set-active');
    Route::delete('/academic-years/{academic_year}', [AcademicYearController::class, 'destroy'])->name('admin.academic-years.destroy');

    // Manajemen Siswa
    Route::get('/students', [StudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('admin.students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('admin.students.store');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

    // Input Nilai / Assessment Kriteria SMART
    Route::get('/students/{student}/assessment', [AssessmentController::class, 'edit'])->name('admin.students.assessment.edit');
    Route::put('/students/{student}/assessment', [AssessmentController::class, 'update'])->name('admin.students.assessment.update');

   // Rute untuk download template sample excel siswa
Route::get('/students/sample-excel', [App\Http\Controllers\StudentController::class, 'downloadSample'])->name('admin.students.sample-excel');
// Tambahkan baris rute detail ini jika belum tercover oleh Resource Controller
Route::get('/companies/{company}', [App\Http\Controllers\CompanyController::class, 'show'])->name('admin.companies.show');

// Rute untuk memproses file excel yang di-upload
Route::post('/students/import', [App\Http\Controllers\StudentController::class, 'import'])->name('admin.students.import');

// Rute Penyesuaian Manual (Manual Override) Penempatan
Route::get('/placements/{placement}/edit', [App\Http\Controllers\SpkController::class, 'edit'])->name('admin.placements.edit');
Route::put('/placements/{placement}', [App\Http\Controllers\SpkController::class, 'update'])->name('admin.placements.update');

Route::get('spk/export-excel', [App\Http\Controllers\SpkController::class, 'exportExcel'])->name('admin.spk.export-excel');
Route::get('/criterias', [App\Http\Controllers\CriterionController::class, 'index'])->name('admin.criterias.index');
Route::put('/criterias/update', [App\Http\Controllers\CriterionController::class, 'update'])->name('admin.criterias.update');
});

// MATIKAN/KOMENTARI baris ini agar rute default Breeze yang berbasis email tidak menimpa sistem kita
// require __DIR__.'/auth.php';