<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminController;
use App\Http\Controllers\siswaController;
use App\Http\Controllers\kontenController;
use App\Http\Controllers\kbmController;

// Landing page
Route::get('/', [kontenController::class, 'landing'])->name('landing');

// Register
Route::get('/register', [adminController::class, 'formRegister'])->name('formRegister');
Route::post('/register', [adminController::class, 'prosesRegister'])->name('prosesRegister');

// Login
Route::get('/login', [adminController::class, 'formLogin'])->name('formLogin');
Route::post('/login', [adminController::class, 'prosesLogin'])->name('prosesLogin');

// Protected routes with ceklogin middleware
Route::middleware(['ceklogin'])->group(function () {
    // Home
    Route::get('/home', [adminController::class, 'home'])->name('home');
    
    // Logout
    Route::get('/logout', [adminController::class, 'logout'])->name('logout');
    
    // CRUD Siswa - Create & Store hanya untuk admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/siswa/create', [siswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa/store', [siswaController::class, 'store'])->name('siswa.store');
    });
    
    // Ajax routes untuk data siswa
    Route::get('/siswa/data', [siswaController::class, 'getData'])->name('siswa.data');
    Route::get('/siswa/search', [siswaController::class, 'search'])->name('siswa.search');
    
    // Edit, Update, Delete siswa untuk semua user yang login
    Route::get('/siswa/{id}/edit', [siswaController::class, 'edit'])->name('siswa.edit');
    Route::post('/siswa/{id}/update', [siswaController::class, 'update'])->name('siswa.update');
    Route::get('/siswa/{id}/delete', [siswaController::class, 'destroy'])->name('siswa.delete');
    
    // Konten
    Route::get('/detil/{id}', [kontenController::class, 'detil'])->name('detil');
    
    // KBM (Jadwal Pelajaran)
    Route::get('/kbm', [kbmController::class, 'index'])->name('kbm.index');
    Route::get('/kbm/data', [kbmController::class, 'getData'])->name('kbm.data');
    Route::get('/kbm/guru/{idguru}', [kbmController::class, 'showByGuru'])->name('kbm.by-guru');
    Route::get('/kbm/kelas/{idwalas}', [kbmController::class, 'showByKelas'])->name('kbm.by-kelas');
});
