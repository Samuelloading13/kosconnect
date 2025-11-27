<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;


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
        // Jika status diset ujui admin (lunas), kirim notifikasi ke penghuni
        if ($request->status === 'sudah membayar') {

            Notification::create([
                'user_id' => $payment->user_id, // penghuni penerima notif
                'title'   => 'Pembayaran Disetujui',
                'message' => 'Pembayaran Anda untuk bulan ' . $payment->keterangan_bulan . ' telah disetujui oleh admin.',
                'link'    => route('penghuni.pembayaran.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
