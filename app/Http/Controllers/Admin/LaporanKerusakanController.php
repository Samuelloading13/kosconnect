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
        $report = Report::findOrFail($id);

        // VALIDASI: Jika sudah selesai, tidak bisa diubah lagi
        if ($report->status == 'selesai') {
            return redirect()->back()->with('error', 'GAGAL: Laporan yang sudah SELESAI tidak dapat diubah lagi statusnya.');
        }

        // Validasi input status yang diterima
        $request->validate([
            'status' => 'required|in:belum ditangani,proses,selesai',
        ]);

        // Update status laporan
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
