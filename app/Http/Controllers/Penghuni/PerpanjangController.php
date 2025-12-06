<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Perpanjang;
use Illuminate\Support\Facades\Auth;

class PerpanjangController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1',
        ]);

        $booking = Booking::where('user_id', Auth::id())
                  ->orderBy('id', 'desc')
                  ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Anda belum memiliki kamar aktif.');
        }

        Perpanjang::create([
            'booking_id'      => $booking->id,
            'user_id'         => Auth::id(),
            'lama_perpanjang' => $request->bulan,
            'status'          => 'pending',
        ]);

            Notification::create([
            'user_id' => 1,
            'title'   => 'Permintaan Perpanjangan',
            'message' => Auth::user()->name . ' mengajukan perpanjangan sewa ' . $request->bulan . ' bulan.',
            'link'    => '/admin/booking',
        ]);

        return redirect()->route('penghuni.dashboard')->with('success', 'Permintaan perpanjangan telah dikirim. Tunggu konfirmasi admin.');
    }
}
