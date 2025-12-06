<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PenghuniController extends Controller
{
    public function index()
    {
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

    public function destroy($id)
    {
        $user = User::with('bookings.room')->findOrFail($id);

        $activeBooking = $user->bookings->where('status', 'disetujui')->last();

        if ($activeBooking) {
            $jatuhTempo = Carbon::parse($activeBooking->tanggal_berakhir_kos);
            $hariIni = Carbon::now();

            $isLewatJatuhTempo = $jatuhTempo->isPast();
            $jumlahHariLewat = $isLewatJatuhTempo ? $jatuhTempo->diffInDays($hariIni) : 0;

            if (!$isLewatJatuhTempo || ($isLewatJatuhTempo && $jumlahHariLewat <= 7)) {
                return redirect()->back()->with('error', 'GAGAL: Akun tidak dapat dihapus karena pengguna masih berstatus sebagai penghuni aktif.');
            }

            // jika nunggak > 7 hari, boleh dihapus -> Kamar jadi 'tersedia'
            if ($activeBooking->room) {
                $activeBooking->room->update(['status' => 'tersedia']);
            }

            $activeBooking->update(['status' => 'selesai']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun penghuni berhasil dihapus (Penghuni dikeluarkan dan kamar dikosongkan).');
    }
}
