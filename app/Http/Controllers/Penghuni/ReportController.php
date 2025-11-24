<?php

namespace App\Http\Controllers\Penghuni;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // READ: Lihat daftar laporan saya
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->latest()->paginate(10);
        return view('penghuni.laporan.index', compact('reports'));
    }

    // CREATE: Form lapor
    public function create()
    {
        return view('penghuni.laporan.create');
    }

    // STORE: Simpan laporan ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => 'belum ditangani',
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laporan_kerusakan', 'public');
        }

        Report::create($data);

        $admin = \App\Models\User::where('role', 'admin')->first();
        if($admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'title' => 'Laporan Baru',
                'message' => 'Laporan dari ' . Auth::user()->name . ': ' . $request->judul,
                'type' => 'warning',
                'link' => route('admin.laporan_kerusakan.index'),
            ]);
        }

        return redirect()->route('penghuni.laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }
}
