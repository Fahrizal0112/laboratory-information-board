<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Edit Data Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-600">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('user.monitorings.update', $monitoring->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nama Part -->
                        <div>
                            <x-input-label for="nama_part" :value="__('Nama Part')" class="text-red-700" />
                            <x-text-input id="nama_part" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500" type="text" name="nama_part" :value="old('nama_part', $monitoring->nama_part)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_part')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Type')" class="text-red-700" />
                            <x-text-input id="type" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500" type="text" name="type" :value="old('type', $monitoring->type)" required />
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- No Mol -->
                        <div class="mt-4">
                            <x-input-label for="no_mol" :value="__('No Mol')" class="text-red-700" />
                            <x-text-input id="no_mol" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500" type="text" name="no_mol" :value="old('no_mol', $monitoring->no_mol)" required />
                            <x-input-error :messages="$errors->get('no_mol')" class="mt-2" />
                        </div>

                        <!-- Background -->
                        <div class="mt-4">
                            <x-input-label for="background" :value="__('Background')" class="text-red-700" />
                            <x-text-input id="background" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500" type="text" name="background" :value="old('background', $monitoring->background)" required />
                            <x-input-error :messages="$errors->get('background')" class="mt-2" />
                        </div>

                        <!-- Request Type -->
                        <div class="mt-4">
                            <x-input-label for="request_type" :value="__('Request')" class="text-red-700" />
                            <select id="request_type" name="request_type" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm" required>
                                <option value="" disabled>Pilih jenis request</option>
                                <option value="Measuring" {{ old('request_type', $monitoring->request) == 'Measuring' ? 'selected' : '' }}>Measuring</option>
                                <option value="Testing" {{ old('request_type', $monitoring->request) == 'Testing' ? 'selected' : '' }}>Testing</option>
                            </select>
                            <x-input-error :messages="$errors->get('request_type')" class="mt-2" />
                        </div>

                        <!-- Part Masuk Lab -->
                        {{-- <div class="mt-4">
                            <x-input-label for="part_masuk_lab" :value="__('Part Masuk Lab')" class="text-red-700" />
                            <x-text-input id="part_masuk_lab" class="block mt-1 w-full border-red-300 focus:border-red-500 focus:ring-red-500" type="date" name="part_masuk_lab" :value="old('part_masuk_lab', $monitoring->part_masuk_lab->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('part_masuk_lab')" class="mt-2" />
                        </div> --}}

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-red-600 hover:text-red-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4" href="{{ route('user.monitorings.index') }}">
                                {{ __('Kembali') }}
                            </a>

                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                {{ __('Perbarui') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 