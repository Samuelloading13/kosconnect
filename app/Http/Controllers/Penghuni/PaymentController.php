<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->latest()->paginate(10);
        return view('penghuni.pembayaran.index', compact('payments'));
    }

    public function create()
    {
        return view('penghuni.pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan_bulan' => 'required|string',
            'durasi_bayar'     => 'required|integer|min:1', // Validasi baru
            'jumlah_bayar'     => 'required|integer|min:0',
            'tanggal_bayar'    => 'required|date',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload Bukti
        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        // Format Keterangan: "Januari 2025 (+3 Bulan)"
        $keterangan = $request->keterangan_bulan;
        if ($request->durasi_bayar > 1) {
            $keterangan .= " (Utk " . $request->durasi_bayar . " Bulan)";
        }

        Payment::create([
            'user_id'          => Auth::id(),
            'keterangan_bulan' => $keterangan, // Simpan keterangan yang sudah diformat
            'jumlah_bayar'     => $request->jumlah_bayar,
            'tanggal_bayar'    => $request->tanggal_bayar,
            'bukti_pembayaran' => $path,
            'status'           => 'pending',
        ]);

        return redirect()->route('penghuni.pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Tunggu konfirmasi admin.');
    }
}
