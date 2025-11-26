<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal

class BookingController extends Controller
{
    public function index()
    {
        // Ambil semua booking dengan relasi user dan room
        $bookings = Booking::with(['user', 'room'])->latest()->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }

    // Method UPDATE untuk Menyetujui/Menolak/Menyelesaikan Booking
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak,selesai']);

        $booking = Booking::findOrFail($id);

        // LOGIKA 1: Jika DISETUJUI
        if ($request->status == 'disetujui') {
            // Hitung Tanggal Berakhir (Jatuh Tempo)
            $tglMulai = Carbon::parse($booking->tanggal_mulai_kos);
            $durasi = (int) $booking->durasi_sewa;

            // Simpan tanggal berakhir ke database
            $booking->tanggal_berakhir_kos = $tglMulai->copy()->addMonths($durasi)->format('Y-m-d');

            // Update Status Kamar jadi 'terisi'
            $booking->room->update(['status' => 'terisi']);

            $pesan = "Booking disetujui. Masa sewa hingga " . Carbon::parse($booking->tanggal_berakhir_kos)->format('d M Y');
        }
        // LOGIKA 2: Jika DITOLAK
        elseif ($request->status == 'ditolak') {
            $pesan = "Booking ditolak.";
        }
        // LOGIKA 3: Jika SELESAI (Penghuni Keluar)
        elseif ($request->status == 'selesai') {
             // Kamar jadi tersedia lagi
             $booking->room->update(['status' => 'tersedia']);
             $pesan = "Masa sewa telah berakhir/selesai.";
        }

        $booking->status = $request->status;
        $booking->save(); // Simpan perubahan (termasuk tanggal_berakhir_kos)

        // Opsional: Kirim Notifikasi (Jika model Notification aktif)
        // \App\Models\Notification::create([...]);

        return redirect()->back()->with('success', $pesan);
    }

    // Method PERPANJANG SEWA (Tambahan)
    public function perpanjang(Request $request, $id)
    {
        $request->validate(['bulan_tambah' => 'required|integer|min:1']);

        $booking = Booking::findOrFail($id);

        // Tambah durasi sewa
        $booking->durasi_sewa += $request->bulan_tambah;

        // Update tanggal berakhir
        if ($booking->tanggal_berakhir_kos) {
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_berakhir_kos)->addMonths($request->bulan_tambah)->format('Y-m-d');
        } else {
            // Fallback jika null (hitung dari awal)
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_mulai_kos)->addMonths($booking->durasi_sewa)->format('Y-m-d');
        }

        $booking->save();

        return redirect()->back()->with('success', 'Masa sewa berhasil diperpanjang ' . $request->bulan_tambah . ' bulan.');
    }
}
