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
use App\Http\Controllers\CriterionController;

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
    
    // Rute Cetak PDF & Surat
    Route::get('/spk/print-pdf', [SpkController::class, 'printPdf'])->name('admin.spk.print');
    Route::get('/spk/placement/{placement}/letter', [SpkController::class, 'printLetter'])->name('admin.spk.letter');
    
    // Rute untuk Manajemen Slot/Gelombang Perusahaan
    Route::resource('company_slots', CompanySlotController::class)->names('admin.company_slots');

    // Manajemen Profil
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
    Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('admin.companies.show');

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
    
    // Rute Template & Import Excel Siswa
    Route::get('/students/sample-excel', [StudentController::class, 'downloadSample'])->name('admin.students.sample-excel');
    Route::post('/students/import', [StudentController::class, 'import'])->name('admin.students.import');

    // Input Nilai / Assessment Kriteria SMART
    Route::get('/students/{student}/assessment', [AssessmentController::class, 'edit'])->name('admin.students.assessment.edit');
    Route::put('/students/{student}/assessment', [AssessmentController::class, 'update'])->name('admin.students.assessment.update');

    // Rute Penyesuaian Manual (Manual Override) Penempatan
    Route::get('/placements/{placement}/edit', [SpkController::class, 'edit'])->name('admin.placements.edit');
    Route::put('/placements/{placement}', [SpkController::class, 'update'])->name('admin.placements.update');

    // Ekspor Excel Rekapitulasi
    Route::get('spk/export-excel', [SpkController::class, 'exportExcel'])->name('admin.spk.export-excel');

    // Manajemen Kriteria / Pembobotan (Full CRUD Resources)
Route::resource('criterias', App\Http\Controllers\CriterionController::class)->names([
    'index'   => 'admin.criterias.index',
    'create'  => 'admin.criterias.create',
    'store'   => 'admin.criterias.store',
    'edit'    => 'admin.criterias.edit',
    'update'  => 'admin.criterias.update',
    'destroy' => 'admin.criterias.destroy',
]);

});

// MATIKAN/KOMENTARI baris ini agar rute default Breeze yang berbasis email tidak menimpa sistem kita
// require __DIR__.'/auth.php';