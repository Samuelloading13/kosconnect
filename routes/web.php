<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ====================================================
// 1. IMPORT CONTROLLER (WAJIB)
// ====================================================

// A. Controller Admin
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\LaporanKerusakanController as AdminLaporanKerusakanController;
use App\Http\Controllers\Admin\PenghuniController as AdminPenghuniController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;

// B. Controller Penghuni & Umum
use App\Http\Controllers\Penghuni\ReportController;
use App\Http\Controllers\Penghuni\PaymentController;
use App\Http\Controllers\Penghuni\DashboardController;
use App\Http\Controllers\BookingController; // <-- Untuk User Booking Awal
use App\Http\Controllers\HomeController;
use App\Models\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Depan (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Detail Kamar (Publik)
Route::get('/kamar/{id}', [HomeController::class, 'show'])->name('kamar.show');

// Proses Booking (User Login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
});

// Dashboard Umum (Redirect sesuai role)
Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {

            $totalPenghuni  = User::where('role', 'penghuni')->count();
            $kamarKosong    = Room::where('status', 'tersedia')->count();
            $kamarTerisi    = Room::where('status', 'terisi')->count();
            $bookingPending = Booking::where('status', 'pending')->count();

            return view('dashboard', compact(
                'totalPenghuni',
                'kamarKosong',
                'kamarTerisi',
                'bookingPending'
            ));
        }

        return redirect()->route('penghuni.dashboard');

    })->middleware(['auth', 'verified'])->name('dashboard');


// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Delete profile dihapus sesuai request
});

require __DIR__.'/auth.php';


// ==========================================
// 2. GRUP RUTE ADMIN (MODUL 1 LENGKAP)
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

    // E. Data Penghuni
    Route::get('/data-penghuni', [AdminPenghuniController::class, 'index'])->name('penghuni.index');
    Route::delete('/data-penghuni/{id}', [AdminPenghuniController::class, 'destroy'])->name('penghuni.destroy');

    // F. Manajemen Booking
    // Rute resource standar untuk index (list) dan update (approve/reject)
    Route::resource('booking', AdminBookingController::class)->only(['index', 'update']);

    // === [AKSI TARGET ANDA] ===
    // Rute POST khusus untuk fitur perpanjangan sewa
    Route::post('/booking/{id}/perpanjang', [AdminBookingController::class, 'perpanjang'])
        ->name('booking.perpanjang');
});


// ==========================================
// 3. GRUP RUTE PENGHUNI (MODUL 2 LENGKAP)
// ==========================================
Route::middleware(['auth', 'role:penghuni'])->prefix('penghuni')->name('penghuni.')->group(function () {

    // Dashboard Khusus Penghuni
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // A. Fitur Laporan Kerusakan & Request
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');

    // B. Fitur Pembayaran
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/buat', [PaymentController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.store');

});
