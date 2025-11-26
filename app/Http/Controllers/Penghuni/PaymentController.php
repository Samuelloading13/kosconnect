<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking; // Diperlukan untuk cek status booking user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // 1. READ (Lihat Riwayat Pembayaran Saya)
    public function index()
    {
        $user = Auth::user();

        // Ambil booking aktif user (jika ada, untuk info tambahan di view)
        $booking = Booking::with('room')->where('user_id', $user->id)->where('status', 'disetujui')->first();

        // Ambil riwayat pembayaran
        $payments = Payment::where('user_id', $user->id)->latest()->paginate(10);

        // Cek apakah bulan ini sudah bayar (Fitur Tambahan: Status Lunas Bulan Ini)
        $sudahBayarBulanIni = false;
        if($booking) {
            $bulanIni = Carbon::now()->format('F Y'); // Contoh format: November 2025

            // Cek di database apakah ada pembayaran dengan keterangan bulan ini yang statusnya pending/sudah membayar
            $cekBayar = Payment::where('user_id', $user->id)
                        ->where('keterangan_bulan', $bulanIni)
                        ->whereIn('status', ['pending', 'sudah membayar'])
                        ->exists();

            if($cekBayar) {
                $sudahBayarBulanIni = true;
            }
        }

        return view('penghuni.pembayaran.index', compact('payments', 'booking', 'sudahBayarBulanIni'));
    }

    // 2. CREATE (Form Konfirmasi Pembayaran)
    public function create()
    {
        return view('penghuni.pembayaran.create');
    }

    // 3. STORE (Simpan Bukti Pembayaran)
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0', // Ganti integer jadi numeric agar lebih fleksibel
            'tanggal_bayar' => 'required|date',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan_bulan' => 'required|string', // Wajib isi bulan apa (sesuai update fitur)
        ]);

        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            // Simpan ke folder 'storage/app/public/bukti_transfer'
            $path = $request->file('bukti_pembayaran')->store('bukti_transfer', 'public');
        }

        Payment::create([
            'user_id' => Auth::id(),
            'jumlah_bayar' => $request->jumlah_bayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status' => 'pending', // Status awal selalu pending
            'bukti_pembayaran' => $path,
            'keterangan_bulan' => $request->keterangan_bulan, // Simpan keterangan bulan
        ]);

        return redirect()->route('penghuni.pembayaran.index')
            ->with('success', 'Konfirmasi pembayaran berhasil dikirim. Menunggu verifikasi Admin.');
    }
}
