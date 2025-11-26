<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'nama_kamar'    => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status'        => 'required|in:tersedia,terisi',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_kamar', 'public');
        }

        Room::create($validated);

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Kamar berhasil ditambahkan.');
    }

    // 4. EDIT (Tampilkan Form Edit dengan Data Lama)
    public function edit($id)
    {
        $kamar = Room::findOrFail($id);
        return view('admin.kamar.edit', compact('kamar'));
    }

    // 5. UPDATE (Simpan Perubahan)
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

        // Jika ada foto baru, hapus foto lama dan upload baru
        if ($request->hasFile('foto')) {
            if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
                Storage::disk('public')->delete($kamar->foto);
            }

            $validated['foto'] = $request->file('foto')->store('foto_kamar', 'public');
        }

        $kamar->update($validated);

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Data kamar berhasil diperbarui.');
    }

    // 6. DESTROY (Hapus Data - Dengan Validasi)
    public function destroy($id)
    {
        $kamar = Room::findOrFail($id);

        // === UPDATE 1: CEGAH HAPUS JIKA TERISI ===
        if ($kamar->status == 'terisi') {
            // Redirect kembali dengan pesan error (flash message 'error' perlu ditangani di view)
            return redirect()->route('admin.kamar.index')
                ->with('error', 'GAGAL: Kamar tidak bisa dihapus karena sedang terisi (Ada Penghuni).');
        }

        // === UPDATE 2: HAPUS FOTO FISIK JIKA ADA ===
        if ($kamar->foto && Storage::disk('public')->exists($kamar->foto)) {
            Storage::disk('public')->delete($kamar->foto);
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')
                        ->with('success', 'Kamar berhasil dihapus.');
    }
}
