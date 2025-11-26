<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon; // Wajib import Carbon

class PenghuniController extends Controller
{
    public function index()
    {
        // Filter: Hanya tampilkan penghuni yang punya booking 'disetujui'
        $penghuni = User::where('role', 'penghuni')
            ->whereHas('bookings', function($q) {
                $q->where('status', 'disetujui');
            })
            ->with(['bookings' => function($q) {
                $q->where('status', 'disetujui')->latest();
            }, 'bookings.room'])
            ->latest()
            ->paginate(10);

        return view('admin.penghuni.index', compact('penghuni'));
    }

    // === UPDATE UTAMA DI SINI ===
    public function destroy($id)
    {
        $user = User::with('bookings.room')->findOrFail($id);

        // 1. Cek Booking Aktif
        $activeBooking = $user->bookings->where('status', 'disetujui')->last();

        if ($activeBooking) {
            $jatuhTempo = Carbon::parse($activeBooking->tanggal_berakhir_kos);
            $hariIni = Carbon::now();

            // Hitung selisih hari
            // Jika jatuh tempo masa lalu, diffInDays return positif
            $isLewatJatuhTempo = $jatuhTempo->isPast();
            $jumlahHariLewat = $isLewatJatuhTempo ? $jatuhTempo->diffInDays($hariIni) : 0;

            // LOGIKA PROTEKSI:
            // Dilarang hapus jika: (Masih Aktif/Lunas) ATAU (Nunggak tapi <= 7 hari)
            if (!$isLewatJatuhTempo || ($isLewatJatuhTempo && $jumlahHariLewat <= 7)) {
                return redirect()->back()->with('error', 'GAGAL: Penghuni ini masih aktif atau masa tenggang belum lewat 7 hari. Tidak bisa dihapus.');
            }

            // LOGIKA EVICTION (PENGUSIRAN OTOMATIS):
            // Jika nunggak > 7 hari, boleh dihapus -> Kamar jadi 'tersedia'
            if ($activeBooking->room) {
                $activeBooking->room->update(['status' => 'tersedia']);
            }

            // Opsional: Set status booking jadi 'selesai' atau biarkan terhapus cascade (tergantung setting DB)
            $activeBooking->update(['status' => 'selesai']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun penghuni berhasil dihapus (Penghuni dikeluarkan dan kamar dikosongkan).');
    }
}
