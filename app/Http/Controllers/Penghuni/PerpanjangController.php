<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Perpanjang; // pastikan model ini sudah dibuat
use Illuminate\Support\Facades\Auth;

class PerpanjangController extends Controller
{
    /**
     * Penghuni mengajukan permintaan perpanjangan kamar
     */
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1',
        ]);

        // Cek booking aktif penghuni
        $booking = Booking::where('user_id', Auth::id())
                  ->orderBy('id', 'desc')
                  ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Anda belum memiliki kamar aktif.');
        }

        // Simpan data perpanjangan
        Perpanjang::create([
            'booking_id'      => $booking->id,
            'user_id'         => Auth::id(),
            'lama_perpanjang' => $request->bulan,
            'status'          => 'pending', // admin nanti yang acc
        ]);

            Notification::create([
            'user_id' => 1, // diasumsikan admin id=1 (atau ganti sesuai sistem kamu)
            'title'   => 'Permintaan Perpanjangan',
            'message' => Auth::user()->name . ' mengajukan perpanjangan sewa ' . $request->bulan . ' bulan.',
            'link'    => '/admin/booking', // admin melihat daftar booking
        ]);

        return redirect()->route('penghuni.dashboard')->with('success', 'Permintaan perpanjangan telah dikirim. Tunggu konfirmasi admin.');
    }
}
