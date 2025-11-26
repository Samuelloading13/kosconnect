<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 1. READ (Lihat Daftar Pembayaran Masuk)
    public function index()
    {
        // Eager loading relasi 'user', 'booking', dan 'room' agar efisien
        // Pastikan model User punya relasi bookings() dan Booking punya relasi room()
        $payments = Payment::with(['user.bookings.room'])->latest()->paginate(10);

        return view('admin.pembayaran.index', compact('payments'));
    }

    // 2. UPDATE (Validasi Pembayaran)
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // === VALIDASI PENGUNCI (FITUR BARU) ===
        // Jika status sudah 'sudah membayar' (Lunas), admin dilarang mengubahnya lagi.
        if ($payment->status == 'sudah membayar') {
            return redirect()->back()->with('error', 'GAGAL: Pembayaran yang sudah LUNAS tidak dapat diubah statusnya.');
        }

        // Validasi input status yang diperbolehkan
        $request->validate([
            'status' => 'required|in:pending,sudah membayar,belum bayar'
        ]);

        // Simpan perubahan status
        $payment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
