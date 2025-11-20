<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Validasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bukti</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse($payments as $pay)
                                    <tr>
                                        <td class="px-6 py-4">{{ $pay->user->name }}</td>
                                        <td class="px-6 py-4">{{ $pay->tanggal_bayar }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($pay->jumlah_bayar) }}</td>
                                        <td class="px-6 py-4">
                                            @if($pay->bukti_pembayaran)
                                                <a href="{{ Storage::url($pay->bukti_pembayaran) }}" target="_blank" class="text-blue-500 underline">Lihat</a>
                                            @else - @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $pay->status == 'sudah membayar' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                                {{ ucfirst($pay->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <!-- Form Update Status -->
                                            <form action="{{ route('admin.pembayaran.update', $pay->id) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white mr-2">
                                                    <option value="pending" {{ $pay->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="sudah membayar" {{ $pay->status == 'sudah membayar' ? 'selected' : '' }}>Terima (Lunas)</option>
                                                    <option value="belum bayar" {{ $pay->status == 'belum bayar' ? 'selected' : '' }}>Tolak</option>
                                                </select>
                                                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
                                                    Simpan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4">Tidak ada data pembayaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $payments->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
