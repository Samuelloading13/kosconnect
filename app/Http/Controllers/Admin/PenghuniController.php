<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenghuniController extends Controller
{
    public function index()
    {
        // Ambil semua user yang role-nya 'penghuni'
        $penghuni = User::where('role', 'penghuni')->latest()->paginate(10);
        return view('admin.penghuni.index', compact('penghuni'));
    }

    // Fungsi hapus user (jika perlu bersih-bersih akun spam)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Akun penghuni berhasil dihapus.');
    }
}
