<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;


class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user.bookings.room'])->latest()->paginate(10);

        return view('admin.pembayaran.index', compact('payments'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status == 'sudah membayar') {
            return redirect()->back()->with('error', 'GAGAL: Pembayaran yang sudah LUNAS tidak dapat diubah statusnya.');
        }

        $request->validate([
            'status' => 'required|in:pending,sudah membayar,belum bayar'
        ]);

        $payment->update(['status' => $request->status]);
        if ($request->status === 'sudah membayar') {

            Notification::create([
                'user_id' => $payment->user_id,
                'title'   => 'Pembayaran Disetujui',
                'message' => 'Pembayaran Anda untuk bulan ' . $payment->keterangan_bulan . ' telah disetujui oleh admin.',
                'link'    => route('penghuni.pembayaran.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
