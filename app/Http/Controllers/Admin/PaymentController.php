<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // READ: Lihat semua pembayaran masuk
    public function index()
    {
        // Ambil data payment beserta data usernya (eager loading)
        $payments = Payment::with('user')->latest()->paginate(10);
        return view('admin.pembayaran.index', compact('payments'));
    }

    // UPDATE: Ubah status (Pending -> Sudah Membayar)
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required']);
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $request->status]);

        // NOTIF KE PENGHUNI: Status pembayaran diperbarui
        $pesan = $request->status == 'sudah membayar' ? 'Pembayaran Anda telah diterima. Terima kasih!' : 'Pembayaran Anda ditolak/pending. Cek detailnya.';
        $tipe = $request->status == 'sudah membayar' ? 'success' : 'warning';

        \App\Models\Notification::create([
            'user_id' => $payment->user_id,
            'title' => 'Status Pembayaran Diperbarui',
            'message' => $pesan,
            'type' => $tipe,
            'link' => route('penghuni.pembayaran.index'),
        ]);

        return redirect()->back()->with('success', 'Status diperbarui.');
    }
}
