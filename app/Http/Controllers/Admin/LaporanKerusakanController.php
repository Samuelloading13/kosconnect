<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class LaporanKerusakanController extends Controller
{
    // READ: Lihat semua laporan kerusakan
    public function index()
    {
        $laporan = Report::with('user')->latest()->paginate(10);
        return view('admin.laporan_kerusakan.index', compact('laporan'));
    }

    // UPDATE: Ubah status (Belum Ditangani -> Proses -> Selesai)
    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required']);
        $report = \App\Models\Report::findOrFail($id); // Sesuaikan modelnya
        $report->update(['status' => $request->status]);

        // NOTIF KE PENGHUNI
        \App\Models\Notification::create([
            'user_id' => $report->user_id,
            'title' => 'Update Laporan Kerusakan',
            'message' => 'Status laporan "' . $report->judul . '" berubah menjadi: ' . ucfirst($request->status),
            'type' => 'info',
            'link' => route('penghuni.laporan.index'),
        ]);

        return redirect()->back()->with('success', 'Status laporan diperbarui.');
    }
}
