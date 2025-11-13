@props(['disabled' => false])

<div class="relative">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
        'class' =>
            'datepicker dark:bg-gray-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:text-white/90 dark:placeholder:text-white/30',
        'type' => 'text',
        'placeholder' => 'Pilih tanggal...',
    ]) !!}>

    {{-- Ikon Font Awesome menggantikan SVG --}}
    <span
        class="pointer-events-none absolute top-0 right-0 flex h-full w-11 items-center justify-center text-gray-500 dark:text-gray-400">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
