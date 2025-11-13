<div class="mx-auto max-w-(--breakpoint-2xl) p-2 md:p-2">
    {{-- Hapus x-data="{ pageName: `Blank Page` }" --}}
    <div class="px-4 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-title-md2 font-semibold text-gray-800 dark:text-gray-100">
            {{-- Slot Judul Halaman --}}
            {{ $judul ?? ($breadcrumbTitle ?? 'Dashboard') }}
        </h2>

        <nav>
            <ol class="flex items-center gap-1.5">
                <li>
                    {{-- Link Home (Selalu Ada) --}}
                    <a class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500"
                        href="{{ route('dashboard') }}">
                        Home
                        <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="currentColor"
                                stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>

                {{-- Slot Breadcrumb Dinamis akan Dirender Di Sini --}}
                @if (isset($breadcrumb))
                    {{ $breadcrumb }}
                @else
                    <li class="text-sm text-gray-800 dark:text-gray-200">/ Dashboard</li>
                @endif

            </ol>
        </nav>
    </div>
</div>
