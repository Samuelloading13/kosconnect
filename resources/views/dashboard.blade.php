<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role == 'admin')
                <!-- STATISTIK ADMIN -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-bold text-blue-800 text-sm uppercase">Total Penghuni</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\User::where('role', 'penghuni')->count() }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-bold text-green-800 text-sm uppercase">Kamar Tersedia</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\Room::where('status', 'tersedia')->count() }}</p>
                    </div>
                    <div class="bg-red-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-bold text-red-800 text-sm uppercase">Kamar Terisi</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\Room::where('status', 'terisi')->count() }}</p>
                    </div>
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-sm">
                        <h3 class="font-bold text-yellow-800 text-sm uppercase">Booking Pending</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-2">{{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-bold text-lg mb-2">Selamat Datang, Admin!</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Anda dapat mengelola data kamar, memvalidasi pembayaran, dan memantau laporan penghuni melalui menu di atas.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-bold text-lg mb-2">Selamat Datang, {{ auth()->user()->name }}!</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Terima kasih telah bergabung di KosConnect.
                        </p>

                        <!-- Tombol Cepat -->
                        <div class="flex space-x-4">
                            <a href="{{ route('penghuni.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Lihat Dashboard Saya
                            </a>
                            <a href="/" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cari Kamar Lain
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
