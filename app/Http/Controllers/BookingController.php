<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal_mulai_kos' => 'required|date',
            'durasi_sewa' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
            'ktp_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $existingBooking = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'GAGAL: Anda sudah memiliki booking yang aktif atau sedang menunggu persetujuan.');
        }

        $ktpPath = null;
        if ($request->hasFile('ktp_foto')) {
            $ktpPath = $request->file('ktp_foto')->store('ktp_user', 'public');
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'tanggal_mulai_kos' => $request->tanggal_mulai_kos,
            'durasi_sewa' => $request->durasi_sewa,
            'status' => 'pending',
            'catatan' => $request->catatan,
            'ktp_foto' => $ktpPath,
        ]);

        $admin = User::where('role', 'admin')->first();
        if($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Booking Masuk Baru',
                'message' => Auth::user()->name . ' mengajukan booking baru. Mohon cek KTP dan validasi.',
                'type' => 'info',
                'link' => route('admin.booking.index'),
            ]);
        }

        $user = Auth::user();
        $adminWa = '6287756205689';

        $pesan = "Halo Admin, saya baru saja mengajukan sewa kamar di KosConnect.\n\n";
        $pesan .= "Nama: " . $user->name . "\n";
        $pesan .= "Kamar: " . $booking->room->nama_kamar . "\n";
        $pesan .= "Durasi: " . $booking->durasi_sewa . " Bulan\n";
        $pesan .= "Mohon dicek ya, terima kasih!";

        $waLink = "https://wa.me/{$adminWa}?text=" . urlencode($pesan);

        return redirect()->route('penghuni.dashboard')->with([
            'success' => 'Pengajuan booking berhasil dikirim! Silakan konfirmasi ke WhatsApp agar segera diproses.',
            'waLink' => $waLink
        ]);
    }
}
