<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Selamat datang di panel admin!") }}
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kartu Manajemen User -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Manajemen User') }}</h3>
                        <p class="mb-4">{{ __('Kelola semua user di sistem, tambah user baru, edit atau hapus user yang ada.') }}</p>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kelola User') }}
                        </a>
                    </div>
                </div>
                
                <!-- Tambahkan kartu menu admin lainnya di sini -->
            </div>
        </div>
    </div>
</x-app-layout>
