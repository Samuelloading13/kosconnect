<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Notification;


class LaporanKerusakanController extends Controller
{
    public function index()
    {
        $laporan = Report::with('user')->latest()->paginate(10);
        return view('admin.laporan_kerusakan.index', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        if ($report->status == 'selesai') {
            return back()->with('error', 'Laporan yang sudah SELESAI tidak dapat diubah lagi.');
        }

        $request->validate([
            'status' => 'required|in:belum ditangani,proses,selesai',
        ]);

        $newStatus = $request->status;

        if ($report->status == 'proses' && $newStatus == 'belum ditangani') {
            return back()->with('error', 'Status tidak dapat dikembalikan ke BELUM DITANGANI.');
        }

        $report->update(['status' => $newStatus]);

        // notifikasi untuk penghuni
        $userId = $report->user_id;

        if ($newStatus == 'proses') {
            Notification::create([
                'user_id' => $userId,
                'title'   => 'Laporan Sedang Diproses',
                'message' => 'Laporan kerusakan yang kamu ajukan sedang diproses oleh admin.',
                'type'    => 'info',
                'link'    => '/penghuni/laporan-kerusakan',
            ]);
        }

        if ($newStatus == 'selesai') {
            Notification::create([
                'user_id' => $userId,
                'title'   => 'Laporan Selesai',
                'message' => 'Perbaikan laporan kerusakan kamu telah selesai ditangani.',
                'type'    => 'success',
                'link'    => '/penghuni/laporan-kerusakan',
            ]);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }


}
