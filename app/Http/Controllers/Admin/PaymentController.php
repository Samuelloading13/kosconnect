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
        $request->validate([
            'status' => 'required|in:pending,sudah membayar,belum bayar',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
