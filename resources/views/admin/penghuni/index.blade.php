<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Penghuni Aktif') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar & Masa Sewa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manajemen Sewa</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($penghuni as $p)
                                    @php
                                        // Ambil booking aktif
                                        $bookingAktif = $p->bookings->where('status', 'disetujui')->first();
                                    @endphp

                                    <tr>
                                        {{-- 1. NAMA --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $p->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $p->email }}</div>
                                        </td>

                                        {{-- 2. INFO KAMAR --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($bookingAktif)
                                                <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                    {{ $bookingAktif->room->nama_kamar }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Berakhir: {{ \Carbon\Carbon::parse($bookingAktif->tanggal_berakhir_kos)->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-red-500 text-xs">Data Tidak Valid</span>
                                            @endif
                                        </td>

                                        {{-- 3. STATUS BAYAR --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $bulanIni = date('Y-m');
                                                $bulanLalu = date('Y-m', strtotime('-1 month'));

                                                $lunasBulanIni = $p->payments->filter(function($payment) use ($bulanIni) {
                                                    return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('Y-m') == $bulanIni
                                                           && $payment->status == 'sudah membayar';
                                                })->isNotEmpty();

                                                $lunasBulanLalu = $p->payments->filter(function($payment) use ($bulanLalu) {
                                                    return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('Y-m') == $bulanLalu
                                                           && $payment->status == 'sudah membayar';
                                                })->isNotEmpty();
                                            @endphp

                                            @if($lunasBulanIni)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Lunas Bulan Ini
                                                </span>
                                            @elseif(!$lunasBulanLalu)
                                                <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                    ⚠ Tertunda
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($bookingAktif)
                                                <div class="flex items-center justify-between w-full min-w-[250px] gap-20">

                                                    <div class="inline-block bg-gray-50 border border-gray-200 rounded p-1">
                                                        <form action="{{ route('admin.booking.perpanjang', $bookingAktif->id) }}" method="POST" class="flex items-center space-x-1"
                                                            onsubmit="return confirm('Konfirmasi perpanjangan sewa untuk {{ $p->name }}?');">
                                                            @csrf
                                                            <span class="text-[10px] text-gray-500 uppercase tracking-wider pl-1">Tambah</span>
                                                            <input type="number" name="bulan_tambah" min="1" value="1"
                                                                class="w-10 px-1 py-0 text-xs border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 text-center" required>
                                                            <span class="text-xs text-gray-500">Bln</span>
                                                            <button type="submit" class="bg-indigo-600 text-white text-xs px-2 py-0.5 rounded hover:bg-indigo-700 transition" title="Proses Perpanjangan">
                                                                +
                                                            </button>
                                                        </form>
                                                    </div>

                                                    <form action="{{ route('admin.booking.update', $bookingAktif->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">

                                                        <button type="submit"
                                                                class="flex items-center bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded shadow transition gap-2 ml-4"
                                                                onclick="return confirm('KONFIRMASI CHECKOUT:\n\nApakah Anda yakin ingin menyelesaikan masa sewa penghuni ini?\n\n- Status kamar akan kembali TERSEDIA.\n- Penghuni akan dihapus dari daftar aktif.')">

                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                            </svg>

                                                            Checkout
                                                        </button>
                                                    </form>

                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                                            Tidak ada penghuni yang sedang menyewa kamar saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $penghuni->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
