<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Tambah Running Text') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.running-texts.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="text" class="block text-sm font-medium text-gray-700">Teks</label>
                            <input type="text" name="text" id="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" value="{{ old('text') }}" required>
                            @error('text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="active" class="inline-flex items-center">
                                <input type="checkbox" name="active" id="active" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" {{ old('active', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.running-texts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 