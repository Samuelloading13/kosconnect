<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ====================================================
// IMPORT CONTROLLER
// ====================================================
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\LaporanKerusakanController as AdminLaporanKerusakanController;
use App\Http\Controllers\Admin\PenghuniController as AdminPenghuniController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController; // <-- TAMBAHAN PENTING

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan (Publik)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar/{id}', [HomeController::class, 'show'])->name('kamar.show');

// Dashboard Umum (Redirect otomatis sesuai role)
Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect()->route('admin.kamar.index');
    } elseif (auth()->user()->role == 'penghuni') {
        return redirect()->route('penghuni.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group Route Login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Booking (User)
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // ==========================================
    // ROUTE NOTIFIKASI (BARU)
    // ==========================================
    Route::get('/notifikasi/{id}', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.readAll');
});

require __DIR__.'/auth.php';

// ==========================================
// GRUP RUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Manajemen Kamar
    Route::resource('kamar', RoomController::class);
    // Laporan Pemasukan
    Route::get('laporan-pemasukan', [AdminLaporanController::class, 'index'])->name('laporan.pemasukan');
    // Validasi Pembayaran
    Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('pembayaran.index');
    Route::patch('/pembayaran/{id}', [AdminPaymentController::class, 'update'])->name('pembayaran.update');
    // Laporan Kerusakan
    Route::get('/laporan-kerusakan', [AdminLaporanKerusakanController::class, 'index'])->name('laporan_kerusakan.index');
    Route::patch('/laporan-kerusakan/{id}', [AdminLaporanKerusakanController::class, 'update'])->name('laporan_kerusakan.update');
    // Data Penghuni
    Route::get('/data-penghuni', [AdminPenghuniController::class, 'index'])->name('penghuni.index');
    Route::delete('/data-penghuni/{id}', [AdminPenghuniController::class, 'destroy'])->name('penghuni.destroy');
    // Konfirmasi Booking
    Route::get('/booking-masuk', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::patch('/booking-masuk/{id}', [AdminBookingController::class, 'update'])->name('booking.update');
});

// ==========================================
// GRUP RUTE PENGHUNI
// ==========================================
Route::middleware(['auth', 'role:penghuni'])->prefix('penghuni')->name('penghuni.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Penghuni\DashboardController::class, 'index'])->name('dashboard');
    // Pembayaran
    Route::get('/pembayaran', [App\Http\Controllers\Penghuni\PaymentController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran', [App\Http\Controllers\Penghuni\PaymentController::class, 'store'])->name('pembayaran.store');
    // Laporan
    Route::resource('laporan', App\Http\Controllers\Penghuni\ReportController::class)->only(['index', 'create', 'store']);
});
