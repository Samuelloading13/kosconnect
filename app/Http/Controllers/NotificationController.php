<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsReadAndRedirect($id)
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);

        if (!$notification) {
            return redirect()->back();
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        if (empty($notification->link)) {
            return redirect()->route('penghuni.dashboard');
        }

        return redirect($notification->link);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return redirect()->back();
    }
}
