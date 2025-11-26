<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Penghuni Terdaftar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Tampilkan Pesan Sukses -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar (Bulan Ini)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse($penghuni as $user)
                                    @php
                                        // Logika Sederhana di View (Idealnya di Controller/Model)
                                        // 1. Cari booking aktif (disetujui) user ini
                                        $bookingAktif = $user->bookings->where('status', 'disetujui')->first();

                                        // 2. Cek apakah user sudah bayar LUNAS untuk bulan ini
                                        $bulanIni = \Carbon\Carbon::now()->format('F Y'); // Contoh: November 2025
                                        $isLunas = false;

                                        // Query cek payment (bisa dipindah ke controller agar lebih rapi, tapi ini works)
                                        $cekBayar = \App\Models\Payment::where('user_id', $user->id)
                                                    ->where('keterangan_bulan', $bulanIni)
                                                    ->where('status', 'sudah membayar')
                                                    ->exists();

                                        if($cekBayar) $isLunas = true;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($bookingAktif)
                                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                    {{ $bookingAktif->room->nama_kamar }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">- Belum ada kamar -</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if(!$bookingAktif)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                                    Non-Aktif
                                                </span>
                                            @elseif($isLunas)
                                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    ✔ LUNAS
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 border border-orange-200 animate-pulse">
                                                    ⚠ BELUM BAYAR
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form action="{{ route('admin.penghuni.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini? Data booking & pembayaran juga akan hilang.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1 rounded hover:bg-red-100 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">
                                            Belum ada penghuni yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $penghuni->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
