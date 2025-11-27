<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kamar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Perhatikan route-nya mengarah ke UPDATE -->
                    <form method="POST" action="{{ route('admin.kamar.update', $kamar->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Penting! Ubah method menjadi PUT untuk update -->

                        <!-- Nama Kamar -->
                        <div>
                            <x-input-label for="nama_kamar" :value="__('Nama Kamar')" />
                            <!-- Value diisi data lama: $kamar->nama_kamar -->
                            <x-text-input id="nama_kamar" class="block mt-1 w-full" type="text" name="nama_kamar" :value="old('nama_kamar', $kamar->nama_kamar)" required />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                            <textarea id="deskripsi" name="deskripsi" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                        </div>

                        <!-- Harga -->
                        <div class="mt-4">
                            <x-input-label for="harga_bulanan" :value="__('Harga / Bulan')" />
                            <x-text-input id="harga_bulanan" class="block mt-1 w-full" type="number" name="harga_bulanan" :value="old('harga_bulanan', $kamar->harga_bulanan)" required />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="terisi" {{ $kamar->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            </select>
                        </div>
                        <!-- Foto Kamar -->
                        <div class="mt-4">
                            <x-input-label for="foto" :value="__('Foto Kamar')" />

                            <!-- Preview Foto Lama -->
                            @if ($kamar->foto)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$kamar->foto) }}" alt="Foto Kamar" class="w-32 h-32 object-cover rounded">
                                </div>
                            @endif

                            <!-- Input Foto Baru -->
                            <input
                                id="foto"
                                type="file"
                                name="foto"
                                class="block mt-1 w-full text-gray-900 dark:text-gray-300"
                                accept="image/png, image/jpg, image/jpeg"
                            >

                            <p class="text-sm text-gray-500 mt-1">
                                Kosongkan jika tidak ingin mengganti foto.
                            </p>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <!-- Tombol Batal -->
                            <a href="{{ route('admin.kamar.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Update Kamar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
