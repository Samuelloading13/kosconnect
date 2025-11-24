<x-guest-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Tombol Kembali -->
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 mb-6 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kamar
            </a>

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl flex flex-col md:flex-row">

                <!-- Kolom Kiri: Gambar & Info -->
                <div class="md:w-1/2 p-8 border-r border-gray-100">
                     <!-- Gambar Utama Placeholder -->
                    <div class="aspect-w-4 aspect-h-3 bg-gray-200 rounded-xl flex items-center justify-center text-gray-400 mb-6">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $kamar->nama_kamar }}</h1>
                    <div class="flex items-center mb-6">
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">{{ $kamar->status }}</span>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Fasilitas & Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line text-sm">{{ $kamar->deskripsi }}</p>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Booking -->
                <div class="md:w-1/2 p-8 bg-gray-50 flex flex-col justify-center">

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500">Harga Sewa</p>
                            <p class="text-4xl font-bold text-blue-600">Rp {{ number_format($kamar->harga_bulanan, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">/ bulan</p>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        @auth
                            <h3 class="text-lg font-bold text-gray-900 mb-4 text-center">Form Pengajuan Booking</h3>

                            <form action="{{ route('booking.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $kamar->id }}">

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Ngekos</label>
                                        <input type="date" name="tanggal_mulai_kos" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Durasi (Bulan)</label>
                                        <div class="flex items-center">
                                            <input type="number" name="durasi_sewa" min="1" value="1" class="w-20 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center mr-2" required>
                                            <span class="text-sm text-gray-500">Bulan</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                        <textarea name="catatan" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Misal: Saya mau survey dulu besok jam 10."></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-md text-sm flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Ajukan Booking Sekarang
                                </button>
                                <p class="text-xs text-center text-gray-400 mt-3">Admin akan memverifikasi data Anda.</p>
                            </form>
                        @else
                            <div class="text-center py-6">
                                <div class="bg-yellow-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <h4 class="text-gray-900 font-bold mb-2">Login Diperlukan</h4>
                                <p class="text-sm text-gray-500 mb-6">Silakan login atau daftar akun baru untuk memesan kamar ini.</p>
                                <div class="flex flex-col space-y-3">
                                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 font-bold transition text-sm">Login Sekarang</a>
                                    <a href="{{ route('register') }}" class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 font-medium transition text-sm">Daftar Akun Baru</a>
                                </div>
                            </div>
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
