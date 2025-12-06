<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking; // Import Booking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Import Carbon

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

        if ($this->isRoomProtected($kamar)) {
            return redirect()->route('admin.kamar.index')
                ->with('error', 'AKSES DITOLAK: Kamar sedang dihuni oleh penyewa aktif (Lunas/Masa Tenggang). Edit dilarang.');
        }

        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, $id)
    {
        $kamar = Room::findOrFail($id);

        if ($this->isRoomProtected($kamar)) {
            return redirect()->route('admin.kamar.index')
                ->with('error', 'GAGAL: Kamar terkunci karena ada penghuni aktif.');
        }

        $validated = $request->validate([
            'nama_kamar'    => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'harga_bulanan' => 'required|integer|min:0',
            'status'        => 'required|in:tersedia,terisi',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

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

    private function isRoomProtected($kamar)
    {
        if ($kamar->status != 'terisi') {
            return false;
        }

        $activeBooking = Booking::where('room_id', $kamar->id)
            ->where('status', 'disetujui')
            ->latest()
            ->first();

        if (!$activeBooking) {
            return false;
        }

        $jatuhTempo = Carbon::parse($activeBooking->tanggal_berakhir_kos);

        if (!$jatuhTempo->isPast()) {
            return true;
        }

        $hariLewat = $jatuhTempo->diffInDays(now());

        if ($hariLewat <= 7) {
            return true;
        }

        return false;
    }
}
