<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment; // <-- Model Pembayaran (Inggris)
use App\Models\User; // <-- Untuk relasi
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Set tanggal default ke bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ambil data laporan dari Model Payment (Inggris)
        $laporan = Payment::with('user') // Ambil relasi ke User
                            ->where('status', 'sudah membayar')
                            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                            ->orderBy('tanggal_bayar', 'desc')
                            ->get();

        $totalPemasukan = $laporan->sum('jumlah_bayar');

        // Kirim ke view (Indonesia)
        return view('admin.laporan.pemasukan', compact('laporan', 'totalPemasukan', 'startDate', 'endDate'));
    }
}
