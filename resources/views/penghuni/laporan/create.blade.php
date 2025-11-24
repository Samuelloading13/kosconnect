<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('penghuni.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="judul" :value="__('Judul Masalah')" />
                        <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" required placeholder="Contoh: AC Kamar 3 Bocor" />
                        <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="deskripsi" :value="__('Deskripsi Detail')" />
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan detail kerusakannya..." required></textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="foto" :value="__('Foto Kerusakan (Opsional)')" />
                        <input id="foto" type="file" name="foto" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-1" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Bantu admin memahami masalah dengan foto.</p>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('penghuni.laporan.index') }}" class="text-gray-600 hover:text-gray-900 underline text-sm">Batal</a>

                        <x-primary-button>
                            {{ __('Kirim Laporan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
