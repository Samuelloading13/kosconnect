<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Pesan Sukses/Error --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pemohon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">Durasi: {{ $booking->durasi_sewa }} Bulan</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->room->nama_kamar }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">

                                    {{-- 1. Tombol Lihat KTP --}}
                                    @if($booking->ktp_foto)
                                        <button onclick="openKtpModal('{{ asset('storage/' . $booking->ktp_foto) }}')"
                                                class="text-blue-600 hover:text-blue-900 mr-3 text-xs border border-blue-200 px-2 py-1 rounded">
                                            Lihat KTP
                                        </button>
                                    @endif

                                    {{-- 2. Logika Tombol Aksi --}}
                                    @if($booking->status == 'pending')
                                        {{-- Tombol Setuju/Tolak --}}
                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-block">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2 font-bold">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-block">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                        </form>

                                    @elseif($booking->status == 'disetujui')
                                        {{-- Form Perpanjang Sewa --}}
                                        <form action="{{ route('admin.booking.perpanjang', $booking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Perpanjang durasi sewa penghuni ini?');">
                                            @csrf
                                            <div class="flex items-center space-x-1 bg-gray-50 p-1 rounded border">
                                                <input type="number" name="bulan_tambah" min="1" value="1" class="w-12 px-1 py-0 text-xs border-gray-300 rounded focus:ring-0" required>
                                                <button type="submit" class="text-green-700 hover:text-green-900 text-xs font-bold px-1">
                                                    +Bln
                                                </button>
                                            </div>
                                        </form>
                                        {{-- Tombol Selesai --}}
                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-block ml-2">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="text-gray-500 hover:text-gray-700 text-xs underline" onclick="return confirm('Tandai sewa sebagai selesai (Penghuni Keluar)?')">
                                                Selesai/Keluar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">Riwayat Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $bookings->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LIHAT KTP --}}
    <div id="ktpModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeKtpModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Foto KTP Pemohon</h3>
                        <div class="mt-2 flex justify-center bg-gray-100 rounded p-2">
                            <img id="ktpImage" src="" alt="KTP" class="max-h-96 w-auto rounded object-contain">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeKtpModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openKtpModal(imageUrl) {
            document.getElementById('ktpImage').src = imageUrl;
            document.getElementById('ktpModal').classList.remove('hidden');
        }
        function closeKtpModal() {
            document.getElementById('ktpModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
