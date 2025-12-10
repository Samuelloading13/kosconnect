<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index()
    {
        $penghuni = User::where('role', 'penghuni')
            ->whereHas('bookings', function($q) {
                $q->where('status', 'disetujui');
            })
            ->with(['bookings' => function($q) {
                $q->where('status', 'disetujui')->with('room');
            }, 'payments'])
            ->latest()
            ->paginate(10);

        return view('admin.penghuni.index', compact('penghuni'));
    }

    public function destroy($id)
    {
        return redirect()->back()->with('error', 'Fitur hapus akun dimatikan.');
    }
}
