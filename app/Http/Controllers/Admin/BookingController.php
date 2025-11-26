<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon; // WAJIB: Import Carbon untuk manipulasi tanggal

class BookingController extends Controller
{
    // 1. READ (Lihat Daftar Booking)
    public function index()
    {
        $bookings = Booking::with(['user', 'room'])->latest()->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }

    // 2. UPDATE (Proses Persetujuan & Hitung Jatuh Tempo)
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak,selesai']);

        $booking = Booking::findOrFail($id);

        // --- LOGIKA HITUNG JATUH TEMPO ---
        if ($request->status == 'disetujui') {

            // 1. Ambil tanggal mulai & durasi
            $tglMulai = Carbon::parse($booking->tanggal_mulai_kos);
            $durasi = (int) $booking->durasi_sewa;

            // 2. Hitung tanggal berakhir (Jatuh Tempo)
            // Contoh: 1 Jan + 1 Bulan = 1 Feb
            $booking->tanggal_berakhir_kos = $tglMulai->copy()->addMonths($durasi)->format('Y-m-d');

            // 3. Ubah status kamar menjadi 'terisi'
            $booking->room->update(['status' => 'terisi']);

            $pesan = "Booking disetujui. Masa sewa aktif hingga " . Carbon::parse($booking->tanggal_berakhir_kos)->format('d M Y');

        }
        elseif ($request->status == 'selesai') {
            // Jika sewa selesai/penghuni keluar, kamar jadi 'tersedia' lagi
            $booking->room->update(['status' => 'tersedia']);
            $pesan = "Masa sewa telah diselesaikan. Kamar kembali tersedia.";
        }
        elseif ($request->status == 'ditolak') {
            $pesan = "Booking ditolak.";
        }

        // Simpan perubahan status & tanggal
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', $pesan);
    }

    // 3. PERPANJANG SEWA (Fitur Tambahan)
    public function perpanjang(Request $request, $id)
    {
        $request->validate(['bulan_tambah' => 'required|integer|min:1']);

        $booking = Booking::findOrFail($id);

        // Tambah durasi sewa
        $booking->durasi_sewa += $request->bulan_tambah;

        // Update tanggal berakhir (Jatuh Tempo Baru)
        if ($booking->tanggal_berakhir_kos) {
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_berakhir_kos)
                                            ->addMonths($request->bulan_tambah)
                                            ->format('Y-m-d');
        } else {
            // Fallback jika tanggal berakhir kosong (misal data lama)
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_mulai_kos)
                                            ->addMonths($booking->durasi_sewa)
                                            ->format('Y-m-d');
        }

        $booking->save();

        return redirect()->back()->with('success', 'Sewa berhasil diperpanjang ' . $request->bulan_tambah . ' bulan.');
    }
}
