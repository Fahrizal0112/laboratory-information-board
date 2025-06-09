<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.monitorings.update', $monitoring->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Informasi Dasar</h3>
                                
                                <!-- Nama Part -->
                                <div class="mb-4">
                                    <x-input-label for="nama_part" :value="__('Nama Part')" />
                                    <x-text-input id="nama_part" class="block mt-1 w-full" type="text" name="nama_part" :value="old('nama_part', $monitoring->nama_part)" required />
                                    <x-input-error :messages="$errors->get('nama_part')" class="mt-2" />
                                </div>

                                <!-- Type -->
                                <div class="mb-4">
                                    <x-input-label for="type" :value="__('Type')" />
                                    <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type', $monitoring->type)" required />
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>

                                <!-- No Mol -->
                                <div class="mb-4">
                                    <x-input-label for="no_mol" :value="__('No Mol')" />
                                    <x-text-input id="no_mol" class="block mt-1 w-full" type="text" name="no_mol" :value="old('no_mol', $monitoring->no_mol)" required />
                                    <x-input-error :messages="$errors->get('no_mol')" class="mt-2" />
                                </div>

                                <!-- Background -->
                                <div class="mb-4">
                                    <x-input-label for="background" :value="__('Background')" />
                                    <x-text-input id="background" class="block mt-1 w-full" type="text" name="background" :value="old('background', $monitoring->background)" required />
                                    <x-input-error :messages="$errors->get('background')" class="mt-2" />
                                </div>

                                <!-- Part Masuk Lab -->
                                <div>
                                    <x-input-label for="part_masuk_lab" :value="__('Part Masuk Lab')" />
                                    <x-text-input id="part_masuk_lab" class="block mt-1 w-full" type="date" name="part_masuk_lab" :value="old('part_masuk_lab', $monitoring->part_masuk_lab ? $monitoring->part_masuk_lab->format('Y-m-d') : '')" required />
                                    <x-input-error :messages="$errors->get('part_masuk_lab')" class="mt-2" />
                                </div>

                                <!-- Request -->
                                <div class="mb-4">
                                    <x-input-label for="request_type" :value="__('Request')" />
                                    <select id="request_type" name="request_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="" disabled>Pilih jenis request</option>
                                        <option value="Measuring" {{ old('request_type', $monitoring->request) == 'Measuring' ? 'selected' : '' }}>Measuring</option>
                                        <option value="Testing" {{ old('request_type', $monitoring->request) == 'Testing' ? 'selected' : '' }}>Testing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('request_type')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">Status dan Jadwal</h3>

                                <!-- Kode Antrian -->
                                <div class="mb-4">
                                    <x-input-label for="kode_antrian" :value="__('Kode Antrian')" />
                                    <x-text-input id="kode_antrian" class="block mt-1 w-full" type="text" name="kode_antrian" :value="old('kode_antrian', $monitoring->kode_antrian)" required />
                                    <x-input-error :messages="$errors->get('kode_antrian')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                        <option value="pending" {{ old('status', $monitoring->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $monitoring->status) == 'approved' ? 'selected' : '' }}>Open</option>
                                        <option value="rejected" {{ old('status', $monitoring->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="in_progress" {{ old('status', $monitoring->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $monitoring->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Catatan -->
                                <div>
                                    <x-input-label for="catatan" :value="__('Catatan')" />
                                    <textarea id="catatan" name="catatan" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('catatan', $monitoring->catatan) }}</textarea>
                                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.monitorings.index') }}">
                                {{ __('Kembali') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 