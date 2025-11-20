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
        $request->validate([
            'status' => 'required|in:belum ditangani,proses,selesai',
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
