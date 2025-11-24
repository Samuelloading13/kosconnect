<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking; // Tambahkan Model Booking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // READ: Lihat riwayat & Form Bayar
    public function index()
    {
        $user = Auth::user();

        // Cari booking yang aktif/disetujui untuk user ini
        $booking = Booking::with('room')
                    ->where('user_id', $user->id)
                    ->where('status', 'disetujui')
                    ->first();

        // Data riwayat pembayaran
        $payments = Payment::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Hitung tagihan bulan ini (Sederhana: cek apakah sudah bayar bulan ini)
        $sudahBayarBulanIni = false;
        if($booking) {
            $bulanIni = Carbon::now()->format('Y-m');
            $cekBayar = Payment::where('user_id', $user->id)
                        ->where('status', 'sudah membayar') // Atau 'pending' jika dianggap sudah lunas sementara
                        ->where('created_at', 'like', $bulanIni . '%')
                        ->exists();

            if($cekBayar) {
                $sudahBayarBulanIni = true;
            }
        }

        return view('penghuni.pembayaran.index', compact('payments', 'booking', 'sudahBayarBulanIni'));
    }

    // CREATE: Kirim bukti pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload file gambar
        $path = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');

        // Simpan ke database
        Payment::create([
            'user_id' => Auth::id(),
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status' => 'pending', // Default pending agar divalidasi admin
            'bukti_pembayaran' => $path,
        ]);

        // NOTIF KE ADMIN: Ada pembayaran baru
        $admin = \App\Models\User::where('role', 'admin')->first();
        if($admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pembayaran Baru Masuk',
                'message' => Auth::user()->name . ' telah mengupload bukti pembayaran sebesar Rp ' . number_format($request->jumlah_bayar),
                'type' => 'info',
                'link' => route('admin.pembayaran.index'),
            ]);
        }

        return redirect()->route('penghuni.pembayaran.index')->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi Admin.');
    }
}
