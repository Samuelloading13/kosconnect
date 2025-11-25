<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Kos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ========================================== -->
            <!-- NOTIFIKASI STATUS TAGIHAN (BAGIAN ATAS)    -->
            <!-- ========================================== -->
            @if(isset($booking) && $booking->room)
                @if(isset($sudahBayarBulanIni) && !$sudahBayarBulanIni)
                    <!-- Notifikasi Belum Bayar (Kuning) -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 shadow-sm rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700 font-bold">
                                    Tagihan Bulan Ini Belum Lunas
                                </p>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Silakan lakukan pembayaran sebesar <strong>Rp {{ number_format($booking->room->harga_bulanan, 0, ',', '.') }}</strong> untuk kamar <strong>{{ $booking->room->nama_kamar }}</strong>.
                                    Jatuh tempo setiap tanggal
                                    <strong>{{ \Carbon\Carbon::parse($booking->tanggal_mulai_kos)->format('d') }}</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Notifikasi Sudah Bayar (Hijau) -->
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 shadow-sm rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-bold">
                                    Terima Kasih!
                                </p>
                                <p class="text-sm text-green-700 mt-1">
                                    Anda sudah melakukan pembayaran untuk bulan ini.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <!-- Notifikasi Belum Punya Kamar (Merah) -->
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 shadow-sm rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-bold">
                                Perhatian
                            </p>
                            <p class="text-sm text-red-700 mt-1">
                                Anda belum terdaftar di kamar manapun. Silakan hubungi admin atau ajukan booking terlebih dahulu.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Alert Sukses Upload -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- ========================================== -->
                <!-- BAGIAN KIRI: FORM UPLOAD BUKTI PEMBAYARAN  -->
                <!-- ========================================== -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-fit">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Bukti Pembayaran</h3>

                    @if(isset($booking) && $booking->room)
                        <form action="{{ route('penghuni.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Input Jumlah (Otomatis dari Harga Kamar & Readonly) -->
                            <div class="mb-4">
                                <x-input-label for="jumlah_bayar" :value="__('Jumlah Transfer (Rp)')" />
                                <x-text-input id="jumlah_bayar" class="block mt-1 w-full bg-gray-100 cursor-not-allowed"
                                              type="number"
                                              name="jumlah_bayar"
                                              required
                                              value="{{ $booking->room->harga_bulanan }}"
                                              readonly />
                                <p class="text-xs text-gray-500 mt-1">*Nominal otomatis sesuai harga kamar Anda.</p>
                                <x-input-error :messages="$errors->get('jumlah_bayar')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="tanggal_bayar" :value="__('Tanggal Transfer')" />
                                <x-text-input id="tanggal_bayar" class="block mt-1 w-full" type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}" />
                                <x-input-error :messages="$errors->get('tanggal_bayar')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="bukti_pembayaran" :value="__('Foto Bukti Transfer')" />
                                <input id="bukti_pembayaran" type="file" name="bukti_pembayaran" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-1" required accept="image/*">
                                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                                <x-input-error :messages="$errors->get('bukti_pembayaran')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Kirim Bukti') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Silakan booking kamar terlebih dahulu untuk melakukan pembayaran.</p>
                        </div>
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- BAGIAN KANAN: RIWAYAT PEMBAYARAN           -->
                <!-- ========================================== -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Pembayaran Saya</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($payment->status == 'sudah membayar')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-900">
                                            <a href="{{ Storage::url($payment->bukti_pembayaran) }}" target="_blank" class="underline">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">
                                            Belum ada riwayat pembayaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
