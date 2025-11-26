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

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            {{-- HEADER TABEL --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Bayar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>

                            {{-- BODY TABEL --}}
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($penghuni as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $p->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $p->email }}</div>
                                    </td>

                                    {{-- Kolom Kamar --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            // Ambil booking aktif terakhir
                                            $bookingAktif = $p->bookings->where('status', 'disetujui')->last();
                                        @endphp

                                        @if($bookingAktif)
                                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                                {{ $bookingAktif->room->nama_kamar }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Status Bayar Canggih --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            // Variabel Waktu
                                            $bulanIni = date('Y-m'); // Contoh: 2025-11
                                            $bulanLalu = date('Y-m', strtotime('-1 month')); // Contoh: 2025-10

                                            // Cek Lunas Bulan Ini
                                            $lunasBulanIni = $p->payments->filter(function($payment) use ($bulanIni) {
                                                return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('Y-m') == $bulanIni
                                                       && $payment->status == 'sudah membayar';
                                            })->isNotEmpty();

                                            // Cek Lunas Bulan Lalu
                                            $lunasBulanLalu = $p->payments->filter(function($payment) use ($bulanLalu) {
                                                return \Carbon\Carbon::parse($payment->tanggal_bayar)->format('Y-m') == $bulanLalu
                                                       && $payment->status == 'sudah membayar';
                                            })->isNotEmpty();
                                        @endphp

                                        @if($lunasBulanIni)
                                            {{-- 1. Kondisi Aman: Sudah bayar bulan ini --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Lunas
                                            </span>
                                        @elseif(!$lunasBulanLalu && $bookingAktif)
                                            {{-- 2. Kondisi Bahaya: Bulan lalu pun belum bayar (Nunggak) --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 animate-pulse">
                                                ⚠ Nunggak
                                            </span>
                                        @else
                                            {{-- 3. Kondisi Warning: Belum bayar bulan ini saja --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                         {{-- Tombol Hapus --}}
                                         <form action="{{ route('admin.penghuni.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini? Data booking & pembayaran juga akan hilang.');">
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

                    <div class="mt-4">
                        {{ $penghuni->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
