<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // 1. READ (Tampilkan semua)
    public function index()
    {
        $kamar = Room::latest()->paginate(10);
        return view('admin.kamar.index', compact('kamar'));
    }

    // 2. CREATE (Tampilkan Form)
    public function create()
    {
        return view('admin.kamar.create');
    }

    // 3. STORE (Simpan Data Baru)
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

    // 4. EDIT (Tampilkan Form Edit dengan Data Lama)
    public function edit($id)
    {
        $kamar = Room::findOrFail($id); // Cari kamar berdasarkan ID
        return view('admin.kamar.edit', compact('kamar'));
    }

    // 5. UPDATE (Simpan Perubahan)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kamar' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,terisi',
        ]);

        $kamar = Room::findOrFail($id);
        $kamar->update($request->all());

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Data kamar berhasil diperbarui.');
    }

    // 6. DESTROY (Hapus Data)
    public function destroy($id)
    {
        $kamar = Room::findOrFail($id);
        $kamar->delete();

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Kamar berhasil dihapus.');
    }
}
