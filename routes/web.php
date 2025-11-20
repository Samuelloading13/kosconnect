<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ====================================================
// IMPORT CONTROLLER ADMIN
// ====================================================
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\LaporanKerusakanController as AdminLaporanKerusakanController;
use App\Http\Controllers\Admin\PenghuniController as AdminPenghuniController; // <-- Controller Baru

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ==========================================
// GRUP RUTE ADMIN (MODUL 1 LENGKAP)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // A. Manajemen Kamar (CRUD)
    Route::resource('kamar', RoomController::class);

    // B. Laporan Pemasukan
    Route::get('laporan-pemasukan', [AdminLaporanController::class, 'index'])->name('laporan.pemasukan');

    // C. Validasi Pembayaran
    Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('pembayaran.index');
    Route::patch('/pembayaran/{id}', [AdminPaymentController::class, 'update'])->name('pembayaran.update');

    // D. Respons Laporan Kerusakan
    Route::get('/laporan-kerusakan', [AdminLaporanKerusakanController::class, 'index'])->name('laporan_kerusakan.index');
    Route::patch('/laporan-kerusakan/{id}', [AdminLaporanKerusakanController::class, 'update'])->name('laporan_kerusakan.update');

    // E. Data Penghuni (BARU)
    Route::get('/data-penghuni', [AdminPenghuniController::class, 'index'])->name('penghuni.index');
    Route::delete('/data-penghuni/{id}', [AdminPenghuniController::class, 'destroy'])->name('penghuni.destroy');
});
