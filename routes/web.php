<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;

// Halaman utama langsung diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route Tamu (Login & Logout)
Route::get('/login', [AuthController::class, 'tampilkanLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang membutuhkan Sesi / Middleware Web
Route::middleware(['web'])->group(function () {

    // ==========================================
    // ROUTE ADMIN
    // ==========================================
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // CRUD Proyek oleh Admin
    Route::get('/admin/proyek', [AdminController::class, 'proyekIndex'])->name('admin.proyek.index');
    Route::post('/admin/proyek', [AdminController::class, 'storeProyek'])->name('admin.proyek.store');
    Route::delete('/admin/proyek/{id}', [AdminController::class, 'destroyProyek'])->name('admin.proyek.destroy');

    // CRUD Tugas oleh Admin
    Route::post('/admin/tugas', [AdminController::class, 'storeTugas'])->name('admin.tugas.store');
    Route::put('/admin/tugas/{id}', [AdminController::class, 'updateTugas'])->name('admin.tugas.update');
    Route::delete('/admin/tugas/{id}', [AdminController::class, 'destroyTugas'])->name('admin.tugas.destroy');

    // Proses penyimpanan nilai (POST)
    Route::post('/admin/tugas/{id}/nilai', [AdminController::class, 'nilaiTugas'])->name('admin.tugas.nilai');

    // Halaman daftar rekap seluruh tugas masuk
    Route::get('/admin/penilaian', [AdminController::class, 'indexPenilaian'])->name('admin.penilaian.index');

    // Halaman detail perorangan untuk menilai tugas tertentu
    Route::get('/admin/penilaian/{id}', [AdminController::class, 'showPenilaian'])->name('admin.penilaian.show');

    // ==========================================
    // ROUTE SISWA
    // ==========================================
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

    // Unggah Berkas & Komentar oleh Siswa
    Route::post('/siswa/tugas/{id}/upload', [SiswaController::class, 'uploadLampiran'])->name('siswa.upload');
    Route::post('/siswa/tugas/{id}/komentar', [SiswaController::class, 'storeKomentar'])->name('siswa.tugas.komentar');

    // Melihat hasil nilai
    Route::get('/siswa/nilai', [SiswaController::class, 'nilaiTugas'])->name('siswa.nilai');
});