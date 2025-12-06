<x-app-layout>
    @if (session('success'))
        <div class="mb-6 p-5 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm animate-fade-in-down">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-lg font-medium text-green-800">Berhasil!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        {{ session('success') }}
                    </div>

                    @if(session('waLink'))
                        <div class="mt-4">
                            <a href="{{ session('waLink') }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                Lanjut ke WhatsApp
                            </a>
                        </div>
                    @endif
                </div>
            </div>
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

            @if($statusPenghuni == 'calon')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang di KosConnect!</h3>
                        <p class="text-gray-600 mb-6">
                            Anda belum terdaftar di kamar manapun. Silakan cari kamar yang tersedia dan ajukan sewa sekarang.
                        </p>

                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            🔍 Cari Kos Sekarang
                        </a>
                    </div>
                </div>

            @elseif($statusPenghuni == 'pending')
                <div class="mb-6 p-5 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div class="ml-3 w-full">
                            <h3 class="text-lg font-medium text-yellow-800">
                                Menunggu Konfirmasi
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                Permintaan sewa Anda sudah masuk dan sedang menunggu persetujuan Admin.
                                <br>
                                Mohon cek berkala atau hubungi admin via WhatsApp untuk mempercepat proses.
                            </div>
                        </div>
                    </div>
                </div>

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
