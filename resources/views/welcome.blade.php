<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KosConnect - Cari Kos Nyaman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 font-sans">

    <!-- Navbar -->
    <div class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <span class="font-bold text-2xl text-blue-600 mr-2">🏠</span>
                <span class="font-bold text-xl text-gray-800">KosConnect</span>

                <!-- Tombol Hubungi Admin -->
                <a href="https://wa.me/6287756205689"
                    class="ml-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-full transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Hubungi Admin
                </a>
            </div>

            <!-- Menu Kanan (Login/Register/Dashboard) -->
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <!-- Kalau sudah login, muncul tombol Dashboard -->
                        @php
                            $dashboardRoute = Auth::user()->role == 'penghuni' ? route('penghuni.dashboard') : route('dashboard');
                        @endphp
                        <a href="{{ $dashboardRoute }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition">
                            Dashboard
                        </a>

                        <!-- Tombol Logout Kecil (Opsional) -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 ml-4 transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <!-- Kalau BELUM login, muncul tombol Masuk & Daftar -->
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md hover:bg-gray-100 transition">
                            Masuk
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-full transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    <!-- Hero Section (Banner Utama) -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-24 text-center overflow-hidden">
        <!-- Hiasan Background -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z"></path>
            </svg>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 tracking-tight leading-tight">
                Temukan Kos Nyaman <br> Tanpa Ribet
            </h1>
            <p class="text-lg md:text-xl opacity-90 mb-8 max-w-2xl mx-auto text-blue-100">
                Jelajahi ribuan pilihan kamar kos strategis dengan fasilitas lengkap. Booking mudah, hidup tenang.
            </p>

            @guest
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-white text-blue-700 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 hover:shadow-xl transition transform hover:-translate-y-1">
                        Cari Kos Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white font-bold py-3 px-8 rounded-full hover:bg-white hover:text-blue-700 transition">
                        Sudah Punya Akun?
                    </a>
                </div>
            @endguest

            @auth
                <a href="#kamar-tersedia" class="inline-block bg-white text-blue-700 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition">
                    Lihat Pilihan Kamar 👇
                </a>
            @endauth
        </div>
    </div>

    <!-- Daftar Kamar -->
    <div id="kamar-tersedia" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Rekomendasi Kamar</h2>
                <p class="text-gray-500 mt-1">Pilihan terbaik yang masih tersedia untukmu.</p>
            </div>
        </div>

        @if($kamar->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-dashed border-gray-300">
                <div class="inline-block p-4 rounded-full bg-gray-50 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum Ada Kamar Tersedia</h3>
                <p class="text-gray-500 mt-1">Admin belum menambahkan data kamar. Silakan cek kembali nanti.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($kamar as $item)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition duration-300 flex flex-col h-full">
                        <!-- Placeholder Gambar Kamar -->
                        <div class="relative h-56 w-full overflow-hidden rounded-t-2xl">
                            @if ($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}"
                                    alt="Foto Kamar"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <span class="absolute top-4 right-4 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md z-20 uppercase tracking-wider">
                                {{ $item->status }}
                            </span>
                        </div>


                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-1 mb-1">{{ $item->nama_kamar }}</h3>
                                <div class="flex items-baseline gap-1">
                                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($item->harga_bulanan, 0, ',', '.') }}</p>
                                    <span class="text-sm text-gray-500 font-medium">/ bulan</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4 mt-auto">
                                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $item->deskripsi }}</p>

                                <a href="{{ route('kamar.show', $item->id) }}" class="block w-full bg-white border-2 border-blue-600 text-blue-600 group-hover:bg-blue-600 group-hover:text-white text-center font-bold py-2.5 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                                    <span>Lihat Detail</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <footer class="bg-white border-t py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm font-medium">&copy; {{ date('Y') }} KosConnect. Solusi Cari Kos Kekinian.</p>
        </div>
    </footer>
</body>
</html>
