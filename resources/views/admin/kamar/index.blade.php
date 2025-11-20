<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('admin.kamar.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 mb-4">
                        Tambah Kamar Baru
                    </a>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kamar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Bulan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                    @forelse ($kamar as $item)
                                        <tr>
                                            <td class="px-6 py-4">{{ $item->nama_kamar }}</td>
                                            <td class="px-6 py-4">Rp {{ number_format($item->harga_bulanan) }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $item->status == 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium flex space-x-4">
                                                <!-- Tombol EDIT -->
                                                <a href="{{ route('admin.kamar.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>

                                                <!-- Tombol HAPUS (Harus pakai Form untuk keamanan) -->
                                                <form action="{{ route('admin.kamar.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Data kosong.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $kamar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
