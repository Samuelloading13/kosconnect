<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kamar Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.kamar.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="nama_kamar" :value="__('Nama Kamar')" />
                            <x-text-input id="nama_kamar" class="block mt-1 w-full" type="text" name="nama_kamar" :value="old('nama_kamar')" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="harga_bulanan" :value="__('Harga / Bulan')" />
                            <x-text-input id="harga_bulanan" class="block mt-1 w-full" type="number" name="harga_bulanan" :value="old('harga_bulanan')" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="tersedia">Tersedia</option>
                                <option value="terisi">Terisi</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
