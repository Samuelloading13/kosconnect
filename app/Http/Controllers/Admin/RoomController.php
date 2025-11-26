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
        // ... (Kode store sama seperti sebelumnya) ...
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

    // === UPDATE 1: PROTEKSI FORM EDIT ===
    public function edit($id)
    {
        $kamar = Room::findOrFail($id);

        // Cek Validasi Proteksi
        if ($this->isRoomProtected($kamar)) {
            return redirect()->route('admin.kamar.index')
                ->with('error', 'AKSES DITOLAK: Kamar sedang dihuni oleh penyewa aktif (Lunas/Masa Tenggang). Edit dilarang.');
        }

        return view('admin.kamar.edit', compact('kamar'));
    }

    // === UPDATE 2: PROTEKSI PROSES UPDATE ===
    public function update(Request $request, $id)
    {
        $kamar = Room::findOrFail($id);

        // Cek Validasi Proteksi (Double Check)
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

    // === FUNGSI BANTUAN (PRIVATE) ===
    // Mengembalikan TRUE jika kamar TERKUNCI (Tidak boleh edit/hapus)
    private function isRoomProtected($kamar)
    {
        if ($kamar->status != 'terisi') {
            return false; // Kamar kosong, bebas edit
        }

        // Cari booking aktif di kamar ini
        $activeBooking = Booking::where('room_id', $kamar->id)
            ->where('status', 'disetujui')
            ->latest()
            ->first();

        if (!$activeBooking) {
            return false; // Status terisi tapi ga ada data booking (aneh, tapi anggap aman diedit)
        }

        $jatuhTempo = Carbon::parse($activeBooking->tanggal_berakhir_kos);

        // LOGIKA:
        // Jika Belum Jatuh Tempo (Masa Depan) -> PROTECTED (True)
        if (!$jatuhTempo->isPast()) {
            return true;
        }

        // Jika Sudah Lewat, Hitung Harinya
        $hariLewat = $jatuhTempo->diffInDays(now());

        // Jika Lewat <= 7 Hari -> MASIH PROTECTED (True)
        if ($hariLewat <= 7) {
            return true;
        }

        // Jika Lewat > 7 Hari -> UNPROTECTED (False - Boleh diedit/diusir)
        return false;
    }
}
