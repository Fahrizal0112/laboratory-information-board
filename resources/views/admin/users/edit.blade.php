<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Department -->
                        <div class="mt-4">
                            <x-input-label for="dept" :value="__('Department')" />
                            <select id="dept" name="dept" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="" disabled>-- Pilih Department --</option>
                                <option value="Quality" {{ old('dept', $user->dept) == 'Quality' ? 'selected' : '' }}>Quality</option>
                                <option value="PPIC" {{ old('dept', $user->dept) == 'PPIC' ? 'selected' : '' }}>PPIC</option>
                                <option value="Engineering" {{ old('dept', $user->dept) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Plant Engineering" {{ old('dept', $user->dept) == 'Plant Engineering' ? 'selected' : '' }}>Plant Engineering</option>
                                <option value="Robot" {{ old('dept', $user->dept) == 'Robot' ? 'selected' : '' }}>Robot</option>
                                <option value="Produksi" {{ old('dept', $user->dept) == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                                <option value="Mold and Material Development" {{ old('dept', $user->dept) == 'Mold and Material Development' ? 'selected' : '' }}>Mold and Material Development</option>
                                <option value="Health Care Unit" {{ old('dept', $user->dept) == 'Health Care Unit' ? 'selected' : '' }}>Health Care Unit</option>
                                <option value="Purchasing" {{ old('dept', $user->dept) == 'Purchasing' ? 'selected' : '' }}>Purchasing</option>
                                <option value="New Project Development" {{ old('dept', $user->dept) == 'New Project Development' ? 'selected' : '' }}>New Project Development</option>
                            </select>
                            <x-input-error :messages="$errors->get('dept')" class="mt-2" />
                        </div>

                        <!-- NPK -->
                        <div class="mt-4">
                            <x-input-label for="npk" :value="__('NPK')" />
                            <x-text-input id="npk" class="block mt-1 w-full" type="text" name="npk" :value="old('npk', $user->npk)" required />
                            <x-input-error :messages="$errors->get('npk')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password (Leave blank if not changing)')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.users.index') }}">
                                {{ __('Back to user list') }}
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>