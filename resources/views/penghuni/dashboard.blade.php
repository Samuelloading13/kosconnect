<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penghuni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(isset($booking) && $booking)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Status Sewa Saya</h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                            <div class="p-4 bg-indigo-50 rounded-lg">
                                <p class="text-sm text-gray-500">Kamar</p>
                                <p class="text-xl font-bold text-indigo-700">{{ $booking->room->nama_kamar ?? '-' }}</p>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Mulai Sewa</p>
                                <p class="font-semibold">{{ $booking->tanggal_mulai_kos->format('d M Y') }}</p>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500">Jatuh Tempo</p>
                                <p class="font-semibold text-red-600">
                                    {{ $booking->tanggal_berakhir_kos ? $booking->tanggal_berakhir_kos->format('d M Y') : '-' }}
                                </p>
                            </div>

                            @php
                                // Perhitungan langsung dari objek tanggal di model
                                $sisaHari = now()->diffInDays($booking->tanggal_berakhir_kos, false);
                            @endphp

                            <div class="p-4 {{ $sisaHari < 0 ? 'bg-red-100' : ($sisaHari < 7 ? 'bg-yellow-50' : 'bg-green-50') }} rounded-lg">
                                <p class="text-sm text-gray-500">Sisa Durasi</p>
                                <p class="text-xl font-bold {{ $sisaHari < 0 ? 'text-red-700' : 'text-gray-800' }}">
                                    @if($sisaHari < 0)
                                        Lewat {{ abs(intval($sisaHari)) }} Hari
                                    @else
                                        {{ intval($sisaHari) }} Hari Lagi
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Anda belum memiliki sewa aktif. Silakan pilih kamar terlebih dahulu.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            </div>
    </div>
</x-app-layout>
