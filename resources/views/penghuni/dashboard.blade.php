<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penghuni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
            </div>

            @if($statusPenghuni == 'calon' || $statusPenghuni == 'ditolak')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-4">
                    <p class="font-bold">Anda belum memiliki kamar aktif.</p>
                    <p>Silakan cari kamar dan ajukan booking terlebih dahulu.</p>
                    <a href="{{ route('home') }}" class="underline text-blue-600">Cari Kamar Kos</a>
                </div>
            @elseif($statusPenghuni == 'pending')
                <div class="bg-blue-100 border-l-4 border-blue-500 p-4 mb-4">
                    <p class="font-bold">Booking Sedang Ditinjau</p>
                    <p>Pengajuan booking Anda untuk kamar <strong>{{ $booking->room->nama_kamar }}</strong> sedang diperiksa Admin.</p>
                </div>
            @elseif($statusPenghuni == 'resmi')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Masukkan card tagihan/laporan di sini sesuai kode sebelumnya -->
                    <div class="bg-white p-6 rounded shadow">
                        <h3 class="font-bold">Status Kamar</h3>
                        <p class="text-green-600">Aktif - {{ $booking->room->nama_kamar }}</p>
                    </div>
                    <!-- ... -->
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
