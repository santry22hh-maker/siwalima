@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            {{-- Langsung tampilkan gambar tanpa pengecekan if/else --}}
            <img src="{{ asset('src/images/logo/logo_bpkh.png') }}" class="logo" alt="{{ config('app.name') }}"
                style="max-height: 75px; width: auto;">
        </a>
    </td>
</tr>
