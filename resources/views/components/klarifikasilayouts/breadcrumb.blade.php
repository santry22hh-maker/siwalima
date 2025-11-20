@php
    // Ambil segmen URL
    $segments = request()->segments();
    $url = '';
@endphp

@if ($segments)
    @foreach ($segments as $segment)
        @php
            // Build URL
            $url .= '/' . $segment;
            // Formatting title: ganti '-' jadi spasi & kapital
            $title = ucfirst(str_replace('-', ' ', $segment));
        @endphp

        <li class="flex items-center gap-1.5">
            <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke="currentColor" stroke-width="1.2"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            {{-- Jika bukan segmen terakhir → jadikan link --}}
            @if (!$loop->last)
                <a href="{{ url($url) }}" class="text-sm font-medium text-gray-500 hover:underline">
                    {{ $title }}
                </a>
            @else
                {{-- Segmen terakhir -> teks --}}
                <span class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                    {{ $title }}
                </span>
            @endif
        </li>
    @endforeach
@else
    {{-- Jika tidak ada segmen → tampilkan default --}}
    <li class="text-sm text-gray-800 dark:text-gray-200">Dashboard</li>
@endif
