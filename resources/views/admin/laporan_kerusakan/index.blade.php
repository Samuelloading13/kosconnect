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

                                            @if($item->status == 'selesai')
                                                {{-- Status sudah selesai (Terkunci) --}}
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-800 font-bold">
                                                        ✔ SELESAI
                                                    </span>
                                                </div>

                                            @else
                                                {{-- Form update status --}}
                                                <form
                                                    action="{{ route('admin.laporan_kerusakan.update', $item->id) }}"
                                                    method="POST"
                                                    class="flex items-center"
                                                    onsubmit="
                                                        var selectedValue = this.querySelector('select[name=\'status\']').value;
                                                        if(selectedValue == 'selesai') {
                                                            return confirm('PERINGATAN:\n\nAnda akan menandai laporan ini sebagai SELESAI.\nStatus akan TERKUNCI dan tidak dapat diubah lagi.\n\nPastikan kerusakan benar-benar sudah diperbaiki.\n\nLanjutkan?');
                                                        }
                                                    "
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <select name="status"
                                                        class="text-xs border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white mr-2">

                                                        {{-- Jika status masih "belum ditangani" --}}
                                                        @if($item->status == 'belum ditangani')
                                                            <option value="belum ditangani" selected>Belum Ditangani</option>
                                                            <option value="proses">Proses</option>
                                                            <option value="selesai">Tandai Selesai</option>
                                                        @endif

                                                        {{-- Jika status sudah "proses", hilangkan opsi "belum ditangani" --}}
                                                        @if($item->status == 'proses')
                                                            <option value="proses" selected>Proses</option>
                                                            <option value="selesai">Tandai Selesai</option>
                                                        @endif
                                                    </select>

                                                    <button type="submit"
                                                        class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs transition">
                                                        Simpan
                                                    </button>
                                                </form>
                                            @endif
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
