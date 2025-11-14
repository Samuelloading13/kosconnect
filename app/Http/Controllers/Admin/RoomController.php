<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room; // <-- IMPORT MODEL
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // READ
    public function index()
    {
        $kamar = Room::latest()->paginate(10); // Variabel B. Indonesia
        return view('admin.kamar.index', compact('kamar')); // View B. Indonesia
    }

    // CREATE (Form)
    public function create()
    {
        return view('admin.kamar.create');
    }

    // CREATE (Proses Simpan)
    public function store(Request $request)
    {
        $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,terisi',
        ]);

        Room::create($request->all());

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Kamar berhasil ditambahkan.');
    }

    // ... (Nanti isi fungsi edit, update, destroy)
}
