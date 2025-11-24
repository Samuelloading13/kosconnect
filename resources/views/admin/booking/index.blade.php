<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->room->nama_kamar }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking->status == 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                @if($booking->status == 'pending')
                                    <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-block">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST" class="inline-block">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Selesai</span>
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
</x-app-layout>
