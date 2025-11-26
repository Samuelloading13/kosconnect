<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Ambil data payment beserta user dan booking (untuk tau kamar)
        // Menggunakan eager loading 'user.bookings.room' agar efisien
        $payments = Payment::with(['user.bookings' => function($query) {
             // Ambil booking yang aktif/disetujui terakhir untuk tau kamar saat ini
             $query->where('status', 'disetujui')->latest();
        }, 'user.bookings.room'])->latest()->paginate(10);

        return view('admin.pembayaran.index', compact('payments'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        // VALIDASI: Jika sudah lunas, tidak bisa diubah lagi statusnya
        if ($payment->status == 'sudah membayar') {
            return redirect()->back()->with('error', 'GAGAL: Pembayaran yang sudah LUNAS tidak dapat diubah statusnya.');
        }

        // Validasi input status yang diterima
        $request->validate([
            'status' => 'required|in:pending,sudah membayar,belum bayar'
        ]);

        // Update status pembayaran
        $payment->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
