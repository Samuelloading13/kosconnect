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
        // 1. Validasi Input
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal_mulai_kos' => 'required|date',
            'durasi_sewa' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500',
            'ktp_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // WAJIB KTP (Maks 2MB)
        ]);

        // 2. Cek apakah user sudah punya booking aktif (Pending/Disetujui)
        // Tujuannya agar satu user tidak spam booking banyak kamar sekaligus
        $existingBooking = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->first();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'GAGAL: Anda sudah memiliki booking yang aktif atau sedang menunggu persetujuan.');
        }

        // 3. Proses Upload KTP
        $ktpPath = null;
        if ($request->hasFile('ktp_foto')) {
            // Simpan di folder 'public/ktp_user'
            $ktpPath = $request->file('ktp_foto')->store('ktp_user', 'public');
        }

        // 4. Simpan Data Booking ke Database
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'tanggal_mulai_kos' => $request->tanggal_mulai_kos,
            'durasi_sewa' => $request->durasi_sewa,
            'status' => 'pending', // Status awal
            'catatan' => $request->catatan,
            'ktp_foto' => $ktpPath,
        ]);

        // 5. Buat Notifikasi untuk Admin (Optional - Uncomment jika Model Notification sudah siap)
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

        // 6. Redirect ke WhatsApp Admin
        $kamar = Room::find($request->room_id);
        $user = Auth::user();
        // GANTI NOMOR DI BAWAH DENGAN NOMOR ADMIN ASLI (Format: 628...)
        $nomorAdmin = '6281234567890';

        // Template Pesan WA
        $pesan = "Halo Admin KosConnect, saya ingin konfirmasi booking kamar:\n\n" .
                 "Nama: *" . $user->name . "*\n" .
                 "Kamar: *" . $kamar->nama_kamar . "*\n" .
                 "Durasi Sewa: *" . $request->durasi_sewa . " Bulan*\n" .
                 "Tgl Mulai: " . $request->tanggal_mulai_kos . "\n\n" .
                 "Saya sudah upload KTP di sistem. Mohon dicek dan diproses. Terima kasih.";

        // Redirect ke URL API WhatsApp
        return redirect("https://wa.me/$nomorAdmin?text=" . urlencode($pesan));
    }
}
