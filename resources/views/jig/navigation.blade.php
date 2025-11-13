<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md shadow-sm fixed top-0 left-0 right-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}"
                        class="text-2xl font-bold text-green-700 hover:text-green-800 transition-colors">
                        KAPITAN
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex space-x-8 ml-10">

                    <a href="/"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('/') ? 'font-semibold text-green-700' : '' }}">Home</a>
                    <a href="/jig"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('jig.index') ? 'font-semibold text-green-700' : '' }}">Statistik</a>
                    <a href="{{ route('spasial.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('spasial.index') ? 'font-semibold text-green-700' : '' }}">
                        Data Spasial</a>
                    <a href="/daftar-permohonan"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('permohonan.index') ? 'font-semibold text-green-700' : '' }}">
                        Permohonan</a>

                    {{-- 

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('dashboard') ? 'font-semibold text-green-700' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('peta.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('peta.index') ? 'font-semibold text-green-700' : '' }}">
                        Peta Interaktif
                    </a>
                    <a href="{{ route('input.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('input.index') ? 'font-semibold text-green-700' : '' }}">
                        Input Data
                    </a>
                    <a href="{{ route('analisis.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('analisis.index') ? 'font-semibold text-green-700' : '' }}">
                        Analisis Foto
                    </a>
                    <a href="{{ route('progress.index') }}"
                        class="flex items-center text-gray-600 hover:text-green-700 transition-colors {{ request()->routeIs('progress.index') ? 'font-semibold text-green-700' : '' }}">
                        Progress Permohonan
                    </a> --}}
                </nav>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden md:flex md:items-center md:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-green-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('peta.index')" :active="request()->routeIs('peta.index')">
                Peta Interaktif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('input.index')" :active="request()->routeIs('input.index')">
                Input Data
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('analisis.index')" :active="request()->routeIs('analisis.index')">
                Analisis Foto
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('progress.index')" :active="request()->routeIs('progress.index')">
                Progress Permohonan
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
