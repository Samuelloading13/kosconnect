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
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController; // <--- TAMBAHKAN INI
use App\Models\Report;
use App\Http\Controllers\Penghuni\PerpanjangController;

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

// Dashboard Umum & Notifikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // Route Dashboard
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
    })->name('dashboard');

    // === [TAMBAHKAN ROUTE NOTIFIKASI DI SINI] ===
    Route::get('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.readAll');
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifikasi.read');
});


// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';


// ==========================================
// 2. GRUP RUTE ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('kamar', RoomController::class);
    Route::get('laporan-pemasukan', [AdminLaporanController::class, 'index'])->name('laporan.pemasukan');
    Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('pembayaran.index');
    Route::patch('/pembayaran/{id}', [AdminPaymentController::class, 'update'])->name('pembayaran.update');
    Route::get('/laporan-kerusakan', [AdminLaporanKerusakanController::class, 'index'])->name('laporan_kerusakan.index');
    Route::patch('/laporan-kerusakan/{id}', [AdminLaporanKerusakanController::class, 'update'])->name('laporan_kerusakan.update');
    Route::get('/data-penghuni', [AdminPenghuniController::class, 'index'])->name('penghuni.index');
    Route::delete('/data-penghuni/{id}', [AdminPenghuniController::class, 'destroy'])->name('penghuni.destroy');
    Route::resource('booking', AdminBookingController::class)->only(['index', 'update']);
    Route::post('/booking/{id}/perpanjang', [AdminBookingController::class, 'perpanjang'])->name('booking.perpanjang');
});


// ==========================================
// 3. GRUP RUTE PENGHUNI
// ==========================================
Route::middleware(['auth', 'role:penghuni'])->prefix('penghuni')->name('penghuni.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('laporan.store');
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/buat', [PaymentController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.store');
    Route::post('/perpanjang/store', [PerpanjangController::class, 'store'])->name('perpanjang.store');
});
