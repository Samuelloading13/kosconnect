<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $kamar = Room::latest()->paginate(10);
        return view('admin.kamar.index', compact('kamar'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kamar'    => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status'        => 'required|in:tersedia,terisi',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_kamar', 'public');
        }

        Room::create($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kamar = Room::findOrFail($id);
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kamar'    => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status'        => 'required|in:tersedia,terisi',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $kamar = Room::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
                Storage::disk('public')->delete($kamar->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto_kamar', 'public');
        }

        $kamar->update($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kamar = Room::findOrFail($id);

        // VALIDASI PENTING: Cegah hapus jika kamar terisi
        if ($kamar->status == 'terisi') {
            return redirect()->route('admin.kamar.index')
                ->with('error', 'GAGAL: Kamar tidak bisa dihapus karena sedang terisi (Ada Penghuni).');
        }

        if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
