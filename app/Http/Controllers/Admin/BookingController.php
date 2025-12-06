<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room'])->latest()->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:disetujui,ditolak,selesai']);

        $booking = Booking::findOrFail($id);

        if ($request->status == 'disetujui') {

            $tglMulai = Carbon::parse($booking->tanggal_mulai_kos);
            $durasi = (int) $booking->durasi_sewa;

            $booking->tanggal_berakhir_kos = $tglMulai->copy()->addMonths($durasi)->format('Y-m-d');

            $booking->room->update(['status' => 'terisi']);

            $pesan = "Booking disetujui. Masa sewa aktif hingga " . Carbon::parse($booking->tanggal_berakhir_kos)->format('d M Y');
            Notification::create([
                'user_id' => $booking->user_id,
                'title'   => 'Booking Disetujui',
                'message' => 'Booking kamar "' . $booking->room->nama_kamar . '" telah disetujui. Masa sewa berlaku sampai ' . Carbon::parse($booking->tanggal_berakhir_kos)->format('d M Y'),
                'type'    => 'success',
                'link' => route('penghuni.dashboard')
            ]);
        }
        elseif ($request->status == 'selesai') {
            $booking->room->update(['status' => 'tersedia']);
            $pesan = "Masa sewa telah diselesaikan. Kamar kembali tersedia.";
        }
        elseif ($request->status == 'ditolak') {
            $pesan = "Booking ditolak.";
        }
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', $pesan);
    }

    public function perpanjang(Request $request, $id)
    {
        $request->validate(['bulan_tambah' => 'required|integer|min:1']);
        $booking = Booking::findOrFail($id);
        $bulanTambah = intval($request->bulan_tambah);
        $booking->durasi_sewa += $bulanTambah;
        if ($booking->tanggal_berakhir_kos) {
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_berakhir_kos)
                ->addMonths($bulanTambah)
                ->format('Y-m-d');
        } else {
            $booking->tanggal_berakhir_kos = Carbon::parse($booking->tanggal_mulai_kos)
                ->addMonths($booking->durasi_sewa)
                ->format('Y-m-d');
        }
        $booking->save();

        Notification::create([
            'user_id' => $booking->user_id, // penghuni
            'title'   => 'Perpanjangan Disetujui',
            'message' => 'Sewa Anda berhasil diperpanjang selama ' . $bulanTambah . ' bulan.',
            'link' => route('penghuni.dashboard')
        ]);

        return redirect()->back()->with('success', 'Sewa berhasil diperpanjang ' . $bulanTambah . ' bulan.');

    }
}
