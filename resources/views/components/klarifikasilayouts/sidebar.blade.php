<aside @mouseenter="$store.sidebar.hoverOpen()" @mouseleave="$store.sidebar.hoverClose()"
    :class="$store.sidebar.open ? 'translate-x-0 lg:w-[290px]' : '-translate-x-full lg:translate-x-0 lg:w-[90px]'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen flex-col overflow-y-hidden border-r border-gray-200 bg-white px-3 dark:border-gray-800 dark:bg-gray-900 lg:static transition-all duration-300">

    <div :class="$store.sidebar.open ? 'justify-between' : 'justify-center'"
        class="flex items-center justify-center gap-2 pt-4 sidebar-header pb-7 px-3">

        {{-- Logo saat sidebar terbuka (Gunakan x-show) --}}
        <a href="/" class="flex justify-center w-full" x-show="$store.sidebar.open">
            <span class="logo">
                <img class="dark:hidden" src="{{ asset('src/images/logo/logo.png') }}" style="height: 36px"
                    alt="Logo" />
                <img class="hidden dark:block" src="{{ asset('src/images/logo/logo.png') }}" style="height: 36px"
                    alt="Logo" />
            </span>
        </a>

        {{-- Logo Icon saat sidebar tertutup (Gunakan x-show) --}}
        <a href="/" class="w-full flex justify-center" x-show="!$store.sidebar.open">
            <img class="logo-icon" src="{{ asset('src/images/logo/logo-icon.png') }}" style="height: 36px"
                alt="Logo" />
        </a>
    </div>
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <nav>
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400"
                    :class="$store.sidebar.open ? 'px-3' : 'text-center'">
                    <span :class="$store.sidebar.open ? 'lg:inline' : 'lg:hidden'">MENU</span>
                    {{-- Icon ... saat tertutup --}}
                    <svg :class="$store.sidebar.open ? 'hidden' : 'lg:block hidden'" class="mx-auto fill-current"
                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="currentColor" />
                    </svg>
                </h3>

                {{-- PERULANGAN UTAMA MENU --}}
                <ul class="flex flex-col gap-2 mb-6">
                    @foreach ($links as $link)
                        {{-- Cek apakah link ini adalah dropdown --}}
                        @if (isset($link['is_dropdown']) && $link['is_dropdown'])
                            {{-- INI ADALAH ITEM DROPDOWN --}}
                            <li x-data="{ open: false }">
                                {{-- Tombol Parent --}}
                                <a href="#" @click.prevent="open = !open"
                                    class="menu-item group flex items-center rounded-lg py-2 w-full"
                                    :class="[
                                        ({{ $link['is_active'] ? 'true' : 'false' }}) ? 'menu-item-active' :
                                        'menu-item-inactive',
                                        !$store.sidebar.open ? 'justify-center' : 'px-3'
                                    ]">

                                    {{-- === PERBAIKAN SINTAKSIS @class (1) === --}}
                                    <i class="{{ $link['icon'] }} w-6 text-center @class([
                                        'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' => !$link[
                                            'is_active'
                                        ],
                                    ])">
                                    </i>

                                    <span class="menu-item-text ml-3"
                                        :class="$store.sidebar.open ? 'lg:inline' : 'lg:hidden'">
                                        {{ $link['name'] }}
                                    </span>

                                    <span :class="$store.sidebar.open ? 'lg:inline ml-auto' : 'lg:hidden'">
                                        <svg class="h-4 w-4 transform transition-transform duration-200"
                                            :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </a>

                                {{-- Daftar Submenu --}}
                                <ul x-show="open" x-transition class="mt-2 space-y-2"
                                    :class="$store.sidebar.open ? 'lg:pl-9' : 'lg:hidden'">

                                    @foreach ($link['submenu'] as $sublink)
                                        <li>
                                            <a href="{{ route($sublink['route']) }}"
                                                class="menu-item group flex items-center rounded-lg px-3 py-2 text-sm"
                                                :class="({{ $sublink['is_active'] ? 'true' : 'false' }}) ? 'menu-item-active' :
                                                'menu-item-inactive'">

                                                <span class="menu-item-text ml-3">
                                                    - {{ $sublink['name'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            {{-- INI ADALAH ITEM LINK BIASA --}}
                            <li>
                                <a href="{{ route($link['route']) }}"
                                    class="menu-item group flex items-center rounded-lg py-2 w-full"
                                    :class="[
                                        ({{ $link['is_active'] ? 'true' : 'false' }}) ? 'menu-item-active' :
                                        'menu-item-inactive',
                                        !$store.sidebar.open ? 'justify-center' : 'px-3'
                                    ]">

                                    {{-- === PERBAIKAN SINTAKSIS @class (2) === --}}
                                    <i class="{{ $link['icon'] }} w-6 text-center @class([
                                        'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' => !$link[
                                            'is_active'
                                        ],
                                    ])">
                                    </i>

                                    <span class="menu-item-text ml-3"
                                        :class="$store.sidebar.open ? 'lg:inline' : 'lg:hidden'">
                                        {{ $link['name'] }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</aside>
