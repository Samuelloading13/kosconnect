<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Masuk') }}
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul & Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse($laporan as $item)
                                    <tr>
                                        <td class="px-6 py-4 align-top">{{ $item->user->name }}</td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-bold">{{ $item->judul }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($item->deskripsi, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            @if($item->foto)
                                                <a href="{{ Storage::url($item->foto) }}" target="_blank">
                                                    <img src="{{ Storage::url($item->foto) }}" class="h-16 w-16 object-cover rounded" alt="Bukti">
                                                </a>
                                            @else - @endif
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $item->status == 'selesai' ? 'bg-green-200 text-green-800' :
                                                   ($item->status == 'proses' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <form action="{{ route('admin.laporan_kerusakan.update', $item->id) }}" method="POST" class="flex flex-col gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="text-sm border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white">
                                                    <option value="belum ditangani" {{ $item->status == 'belum ditangani' ? 'selected' : '' }}>Belum Ditangani</option>
                                                    <option value="proses" {{ $item->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                    <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
                                                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded text-xs w-full">
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4">Tidak ada laporan masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $laporan->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
