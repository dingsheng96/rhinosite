<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Rhinosite')
            <img src="{{ asset('storage/logo.png') }}" class="logo" alt="Rhinosite Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>