<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Pemasukan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="GET" action="{{ route('admin.laporan.pemasukan') }}" class="mb-6 flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                        <div>
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" type="date" name="start_date" :value="$startDate" class="block mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Tanggal Akhir')" />
                            <x-text-input id="end_date" type="date" name="end_date" :value="$endDate" class="block mt-1 w-full" />
                        </div>
                        <div class="self-end">
                            <x-primary-button>Filter</x-primary-button>
                        </div>
                    </form>

                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                        <h3 class="text-lg font-semibold">Total Pemasukan ({{ $startDate }} s/d {{ $endDate }})</h3>
                        <p class="text-2xl font-bold">Rp {{ number_format($totalPemasukan) }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Bayar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penghuni</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @forelse ($laporan as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $item->tanggal_bayar }}</td>
                                        <td class="px-6 py-4">{{ $item->user->name }}</td> <td class="px-6 py-4">Rp {{ number_format($item->jumlah_bayar) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
