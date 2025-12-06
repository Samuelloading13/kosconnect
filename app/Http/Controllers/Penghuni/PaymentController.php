<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $booking = $user->booking()->with('room')->first();

        $sudahBayarBulanIni = Payment::where('user_id', Auth::id())
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->where('status', 'sudah membayar')
            ->exists();

        $payments = Payment::where('user_id', Auth::id())->latest()->paginate(10);

        return view('penghuni.pembayaran.index', compact(
            'payments',
            'booking',
            'sudahBayarBulanIni'
        ));
    }


    public function create()
    {
        return view('penghuni.pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan_bulan' => 'required|string',
            'durasi_bayar'     => 'required|integer|min:1',
            'jumlah_bayar'     => 'required|integer|min:0',
            'tanggal_bayar'    => 'required|date',
            'catatan'          => 'nullable|string|max:255',
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $keterangan = $request->keterangan_bulan;
        if ($request->durasi_bayar > 1) {
            $keterangan .= " (Utk {$request->durasi_bayar} Bulan)";
        }

        Payment::create([
            'user_id'          => Auth::id(),
            'keterangan_bulan' => $keterangan,
            'jumlah_bayar'     => $request->jumlah_bayar,
            'tanggal_bayar'    => $request->tanggal_bayar,
            'bukti_pembayaran' => $path,
            'catatan'          => $request->catatan,
            'status'           => 'pending',
        ]);

        // notif ke admin
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title'   => 'Bukti Pembayaran Baru',
                'message' => 'Ada bukti pembayaran baru dari ' . Auth::user()->name,
                'link'    => route('admin.pembayaran.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->route('penghuni.pembayaran.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Tunggu konfirmasi admin.');
    }

}
