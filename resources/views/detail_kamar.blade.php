<x-detail-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Foto Kamar -->
                        <div>
                            @if($kamar->foto)
                                <img src="{{ Storage::url($kamar->foto) }}" alt="{{ $kamar->nama_kamar }}" class="w-full h-96 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 shadow-inner">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Detail & Form Booking -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $kamar->nama_kamar }}</h1>
                            <p class="text-2xl text-indigo-600 font-bold mb-4">Rp {{ number_format($kamar->harga_bulanan, 0, ',', '.') }} / Bulan</p>

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
                                <h3 class="font-bold text-lg mb-2 text-gray-700 border-b pb-2">Fasilitas & Deskripsi</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ $kamar->deskripsi }}</p>
                            </div>

                            <hr class="my-6 border-gray-200">

                            @auth
                                @if($kamar->status == 'tersedia')
                                    <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm">
                                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Form Pengajuan Booking
                                        </h3>

                                        <!-- FORM UPDATE: enctype ditambahkan di sini -->
                                        <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                            @csrf
                                            <input type="hidden" name="room_id" value="{{ $kamar->id }}">

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Ngekos</label>
                                                <input type="date" name="tanggal_mulai_kos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 ease-in-out" required>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Durasi (Bulan)</label>
                                                <div class="relative rounded-md shadow-sm">
                                                    <input type="number" name="durasi_sewa" min="1" value="1" class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <span class="text-gray-500 sm:text-sm">Bulan</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- INPUT KTP BARU (WAJIB) -->
                                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                                <label class="block text-sm font-medium text-blue-900 mb-2">Upload Foto KTP (Wajib)</label>
                                                <input type="file" name="ktp_foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" accept="image/*" required>
                                                <p class="text-xs text-blue-600 mt-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Nama akun harus sesuai dengan KTP untuk verifikasi.
                                                </p>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                                <textarea name="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Saya akan check-in sore hari..."></textarea>
                                            </div>

                                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out flex justify-center items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                Ajukan Booking & Konfirmasi WA
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-xl text-center shadow-sm">
                                        <svg class="w-12 h-12 mx-auto text-red-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        <h3 class="text-lg font-bold mb-1">Maaf, Kamar Terisi</h3>
                                        <p class="text-sm text-red-600">Saat ini kamar ini sudah ada penghuninya. Silakan cek kamar lain.</p>
                                    </div>
                                @endif
                            @else
                                <div class="bg-gray-50 p-8 rounded-xl text-center border border-gray-200 shadow-sm">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    <h3 class="text-gray-900 font-bold text-lg mb-2">Login Diperlukan</h3>
                                    <p class="mb-6 text-gray-600 text-sm">Silakan login terlebih dahulu untuk membooking kamar ini.</p>
                                    <div class="flex justify-center space-x-4">
                                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium transition duration-150 shadow-sm">Login</a>
                                        <a href="{{ route('register') }}" class="bg-white text-gray-700 border border-gray-300 px-6 py-2.5 rounded-lg hover:bg-gray-50 font-medium transition duration-150 shadow-sm">Daftar Akun</a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-detail-layout>
