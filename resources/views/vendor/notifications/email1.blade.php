<x-mail::message>
    {{-- Greeting --}}
    @if (!empty($greeting))
        # {{ $greeting }}
    @else
        # {{ $level === 'error' ? __('Whoops!') : __('Hallo') }}
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        {{ $line }}
    @endforeach

    {{-- Action Button --}}
    @isset($actionText)
        @php
            $color = match ($level) {
                'success', 'error' => $level,
                default => 'primary',
            };
        @endphp

        <x-mail::button :url="$actionUrl" :color="$color">
            {{ $actionText }}
        </x-mail::button>
    @endisset

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        {{ $line }}
    @endforeach

    {{-- Salutation --}}
    @if (!empty($salutation))
        {{ $salutation }}
    @else
        @lang('Hormat Kami,')<br>
        {{ config('app.name') }}
    @endif

    @isset($actionText)
        <x-slot:subcopy>
            @lang("Jika tombol \":actionText\" tidak berfungsi, salin dan tempel URL di bawah ini ke browser web Anda:", ['actionText' => $actionText])
            {{-- Perbaikan: Gunakan format Link Markdown dan class break-all --}}
            <span class="break-all">[{{ $actionUrl }}]({{ $actionUrl }})</span>
        </x-slot:subcopy>
    @endisset
</x-mail::message>
