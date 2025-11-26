<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pembayaran Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('penghuni.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="keterangan_bulan" :value="__('Mulai Dari Bulan')" />
                            <select name="keterangan_bulan" id="keterangan_bulan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled selected>-- Pilih Bulan --</option>
                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                                    <option value="{{ $bulan }} {{ date('Y') }}">{{ $bulan }} {{ date('Y') }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="durasi_bayar" :value="__('Bayar Berapa Bulan?')" />
                            <x-text-input id="durasi_bayar" class="block mt-1 w-full" type="number" name="durasi_bayar" min="1" value="1" required />
                            <p class="text-xs text-gray-500 mt-1">Contoh: 1 untuk 1 bulan, 3 untuk 3 bulan.</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="jumlah_bayar" :value="__('Total Nominal Transfer (Rp)')" />
                        <x-text-input id="jumlah_bayar" class="block mt-1 w-full" type="number" name="jumlah_bayar" required />
                        <p class="text-xs text-gray-500 mt-1">*Pastikan nominal sesuai dengan Harga Kamar x Jumlah Bulan.</p>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="tanggal_bayar" :value="__('Tanggal Transfer')" />
                        <x-text-input id="tanggal_bayar" class="block mt-1 w-full" type="date" name="tanggal_bayar" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="bukti_pembayaran" :value="__('Bukti Transfer')" />
                        <input id="bukti_pembayaran" type="file" name="bukti_pembayaran" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required />
                    </div>

                    <div class="flex justify-end mt-6">
                        <x-primary-button>
                            {{ __('Kirim Pembayaran') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
