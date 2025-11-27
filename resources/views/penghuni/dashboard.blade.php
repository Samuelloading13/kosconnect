<x-app-layout>
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penghuni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BAGIAN 1: JIKA BELUM PUNYA KOS (STATUS CALON) --}}
            @if($statusPenghuni == 'calon')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang di KosConnect!</h3>
                        <p class="text-gray-600 mb-6">
                            Anda belum terdaftar di kamar manapun. Silakan cari kamar yang tersedia dan ajukan sewa sekarang.
                        </p>

                        {{-- TOMBOL CARI KAMAR (MENGARAH KE HALAMAN DEPAN) --}}
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            🔍 Cari Kos Sekarang
                        </a>
                    </div>
                </div>

            {{-- BAGIAN 2: JIKA STATUS PENDING (MENUNGGU PERSETUJUAN) --}}
            @elseif($statusPenghuni == 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-bold">
                                Permintaan sewa Anda sedang menunggu persetujuan Admin.
                            </p>
                            <p class="text-xs text-yellow-600 mt-1">
                                Mohon tunggu konfirmasi selanjutnya.
                            </p>
                        </div>
                    </div>
                </div>

            {{-- BAGIAN 3: JIKA DITOLAK --}}
            @elseif($statusPenghuni == 'ditolak')
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-bold">
                                Maaf, pengajuan sewa Anda ditolak.
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('home') }}" class="text-sm underline text-red-600 hover:text-red-800">
                                    Cari Kamar Lain
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- BAGIAN 4: KARTU STATISTIK (TAMPIL JIKA SUDAH RESMI) --}}
            @if($statusPenghuni == 'resmi' && $booking)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Kamar Anda</div>

                        <div class="text-2xl font-bold text-indigo-600">
                            {{ $booking->room->nama_kamar ?? '-' }}
                        </div>

                        <div class="text-sm text-gray-600 mt-1">
                            Masa sewa:
                            <span class="font-semibold text-gray-800">
                                {{ $booking->durasi_sewa ?? '0' }} bulan
                            </span>
                        </div>
                    </div>


                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Tagihan Pending</div>
                        <div class="text-2xl font-bold {{ $tagihanPending > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $tagihanPending }}
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Laporan Aktif</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $laporanAktif }}</div>
                    </div>
                </div>
                    <!-- Request Perpanjangan -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mt-6 border border-gray-200">
                        <h3 class="font-bold text-gray-800 text-lg mb-3">
                            Perpanjang Masa Sewa
                        </h3>

                        <p class="text-sm text-gray-600 mb-5 leading-relaxed">
                            Ajukan perpanjangan kamar Anda. Admin akan memproses permintaan ini setelah diajukan.
                        </p>

                        <form action="{{ route('penghuni.perpanjang.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tambah Durasi
                                </label>

                                <div class="flex items-center gap-3">
                                    <input
                                        type="number"
                                        name="bulan"
                                        min="1"
                                        max="12"
                                        class="w-15 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                        required
                                    >
                                    <span class="text-sm text-gray-600">bulan</span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-semibold transition"
                            >
                                Ajukan Perpanjangan
                            </button>
                        </form>
                    </div>
            @endif
        </div>
    </div>
</x-app-layout>
