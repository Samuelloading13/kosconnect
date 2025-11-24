<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Fungsi ini dipanggil saat notifikasi diklik
    public function markAsReadAndRedirect($id)
    {
        // Cari notifikasi milik user yang sedang login
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);

        // Tandai sudah dibaca (hilangkan titik merah)
        $notification->update(['is_read' => true]);

        // Arahkan user ke halaman tujuan (misal: pembayaran atau dashboard)
        // Jika link kosong, default ke dashboard
        return redirect($notification->link ?? route('penghuni.dashboard'));
    }

    // Fungsi untuk tandai semua sudah dibaca
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return redirect()->back();
    }
}
