<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $booking = Booking::with('room')->where('user_id', $userId)->latest()->first();

        // jika belum pernah booking sama sekali ke hal utama
        if (!$booking) {
            return redirect()->route('home')->with('status', 'Silakan pilih dan booking kamar terlebih dahulu.');
        }

        $statusPenghuni = 'calon';
        if ($booking->status == 'disetujui') $statusPenghuni = 'resmi';
        elseif ($booking->status == 'pending') $statusPenghuni = 'pending';
        elseif ($booking->status == 'ditolak') $statusPenghuni = 'ditolak';

        $tagihanPending = 0;
        $laporanAktif = 0;
        $terakhirBayar = null;

        if ($statusPenghuni == 'resmi') {
            $tagihanPending = Payment::where('user_id', $userId)->where('status', 'pending')->count();
            $laporanAktif = Report::where('user_id', $userId)->whereIn('status', ['belum ditangani', 'proses'])->count();
            $terakhirBayar = Payment::where('user_id', $userId)->latest()->first();
        }

        return view('penghuni.dashboard', compact('statusPenghuni', 'booking', 'tagihanPending', 'laporanAktif', 'terakhirBayar'));
    }
}
