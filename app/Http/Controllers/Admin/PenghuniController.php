<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role 'penghuni'
        // Eager load 'bookings' untuk mendapatkan kamar aktif mereka
        // Logika: Ambil booking terakhir yang statusnya 'disetujui'
        $penghuni = User::where('role', 'penghuni')
            ->with(['bookings' => function($q) {
                $q->where('status', 'disetujui')->latest();
            }, 'bookings.room']) // Ambil juga data room dari booking tersebut
            ->latest()
            ->paginate(10);

        return view('admin.penghuni.index', compact('penghuni'));
    }

    // Hapus akun penghuni (jika diperlukan)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Opsional: Cek apakah user masih punya tanggungan pembayaran/booking aktif sebelum dihapus
        // Tapi untuk sekarang kita biarkan admin punya kuasa penuh menghapus.

        $user->delete();

        return redirect()->back()->with('success', 'Akun penghuni berhasil dihapus.');
    }
}
