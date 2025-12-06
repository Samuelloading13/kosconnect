<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $laporan = Payment::with('user')
                            ->where('status', 'sudah membayar')
                            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
                            ->orderBy('tanggal_bayar', 'desc')
                            ->get();

        $totalPemasukan = $laporan->sum('jumlah_bayar');

        return view('admin.laporan.pemasukan', compact('laporan', 'totalPemasukan', 'startDate', 'endDate'));
    }
}
