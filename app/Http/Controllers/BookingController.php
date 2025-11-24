<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal_mulai_kos' => 'required|date',
            'durasi_sewa' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
        ]);

        // 2. Cek Duplikasi (Supaya tidak double booking yang statusnya masih pending)
        $existingBooking = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'Anda sudah memiliki booking yang aktif atau sedang diproses.');
        }

        // 3. Simpan Data Booking ke Database
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'tanggal_mulai_kos' => $request->tanggal_mulai_kos,
            'durasi_sewa' => $request->durasi_sewa,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);

        // 4. Buat Notifikasi di Web (Lonceng)
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Booking Berhasil Diajukan',
            'message' => 'Pengajuan booking Anda telah tercatat. Silakan lanjutkan konfirmasi ke WhatsApp Admin.',
            'type' => 'info',
            'link' => route('penghuni.dashboard'),
        ]);

        // 5. Redirect ke WhatsApp Admin
        $kamar = Room::find($request->room_id);
        $user = Auth::user();

        // Nomor Admin (Sesuai request kamu)
        $nomorAdmin = '6287756205689';

        $pesan = "Halo Admin KosConnect, saya ingin mengajukan booking kamar:\n\n" .
                 "Nama: *$user->name*\n" .
                 "Kamar: *$kamar->nama_kamar*\n" .
                 "Harga: Rp " . number_format($kamar->harga_bulanan, 0, ',', '.') . "\n" .
                 "Mulai: " . date('d M Y', strtotime($request->tanggal_mulai_kos)) . "\n" .
                 "Durasi: $request->durasi_sewa Bulan\n\n" .
                 "Mohon infonya untuk proses selanjutnya. Terima kasih!";

        $waLink = "https://wa.me/$nomorAdmin?text=" . urlencode($pesan);

        // Arahkan user ke Link WhatsApp
        return redirect($waLink);

        $admin = \App\Models\User::where('role', 'admin')->first();
        if($admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'Booking Masuk Baru',
                'message' => Auth::user()->name . ' mengajukan booking baru. Segera cek.',
                'type' => 'info',
                'link' => route('admin.booking.index'),
            ]);
        }
    }
}
