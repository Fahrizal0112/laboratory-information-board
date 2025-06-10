<nav x-data="{ open: false }" class="bg-red-800 border-b border-red-900 text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Menu untuk user -->
                    @if (Auth::check() && Auth::user()->role === 'user')
                        <x-nav-link :href="route('user.monitorings.index')" :active="request()->routeIs('user.monitorings.*')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Data Monitoring Saya') }}
                        </x-nav-link>
                    @endif

                    <!-- Menu untuk admin -->
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Manajemen User') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.monitorings.index')" :active="request()->routeIs('admin.monitorings.index')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Semua Data') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.monitorings.pending')" :active="request()->routeIs('admin.monitorings.pending')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            <span class="relative">
                                {{ __('Data Pending') }}
                                @php
                                    $pendingCount = \App\Models\Monitoring::where('status', 'pending')->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="absolute -top-2 -right-2 bg-white text-red-700 text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $pendingCount }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('admin.monitorings.completed')" :active="request()->routeIs('admin.monitorings.completed')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Data Selesai') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.monitorings.archived')" :active="request()->routeIs('admin.monitorings.archived')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Data Arsip') }}
                        </x-nav-link>

                        <x-nav-link :href="route('admin.running-texts.index')" :active="request()->routeIs('admin.running-texts.*')" class="text-white hover:text-red-200" :activeClass="'border-b-2 border-red-300 text-red-100'">
                            {{ __('Running Text') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown dan Logout Button -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-2">
                <!-- Dropdown Menu -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-red-700 text-sm leading-4 font-medium rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::check())
                            <x-dropdown-link :href="route('profile.edit')" class="hover:bg-red-50">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();"
                                        class="hover:bg-red-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        @else
                            <x-dropdown-link :href="route('login')" class="hover:bg-red-50">
                                {{ __('Log In') }}
                            </x-dropdown-link>
                            
                            @if (Route::has('register'))
                                <x-dropdown-link :href="route('register')" class="hover:bg-red-50">
                                    {{ __('Register') }}
                                </x-dropdown-link>
                            @endif
                        @endif
                    </x-slot>
                </x-dropdown>

                <!-- Tombol Logout Terpisah -->
                @if(Auth::check())
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Logout') }}
                        </button>
                    </form>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-red-200 hover:text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-red-700">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if (Auth::check() && Auth::user()->role === 'user')
                <x-responsive-nav-link :href="route('user.monitorings.index')" :active="request()->routeIs('user.monitorings.*')" class="text-white hover:bg-red-700">
                    {{ __('Data Monitoring Saya') }}
                </x-responsive-nav-link>
            @endif
            
            @if (Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-white hover:bg-red-700">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.monitorings.index')" :active="request()->routeIs('admin.monitorings.*')" class="text-white hover:bg-red-700">
                    {{ __('Manajemen Monitoring') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('admin.monitorings.pending')" :active="request()->routeIs('admin.monitorings.pending')" class="text-white hover:bg-red-700">
                    <span class="relative">
                        {{ __('Data Pending') }}
                        @if($pendingCount > 0)
                            <span class="absolute -top-2 -right-2 bg-white text-red-700 text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.monitorings.completed')" :active="request()->routeIs('admin.monitorings.completed')" class="text-white hover:bg-red-700">
                    {{ __('Data Selesai') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.monitorings.archived')" :active="request()->routeIs('admin.monitorings.archived')" class="text-white hover:bg-red-700">
                    {{ __('Data Arsip') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.running-texts.index')" :active="request()->routeIs('admin.running-texts.*')" class="text-white hover:bg-red-700">
                    {{ __('Running Text') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-red-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                <div class="font-medium text-sm text-red-200">{{ Auth::check() ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-red-700">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-white hover:bg-red-700">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')" class="text-white hover:bg-red-700">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                    
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" class="text-white hover:bg-red-700">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
