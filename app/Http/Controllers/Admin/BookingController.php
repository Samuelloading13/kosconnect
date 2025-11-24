<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room'])->latest()->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak']);
        $booking = Booking::findOrFail($id);

        $booking->update(['status' => $request->status]);

        if ($request->status == 'disetujui') {
            $booking->room->update(['status' => 'terisi']);
            $pesan = "Selamat! Booking disetujui.";
            $tipe = "success";
        } else {
            $pesan = "Maaf, booking ditolak.";
            $tipe = "warning";
        }

        Notification::create([
            'user_id' => $booking->user_id,
            'title' => 'Status Booking',
            'message' => $pesan,
            'type' => $tipe,
            'link' => route('penghuni.dashboard'),
        ]);

        return redirect()->back()->with('success', 'Status diperbarui.');
    }
}
