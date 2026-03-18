@props(['country' => 'us', 'class' => 'inline-block mr-2 w-5 h-3 align-middle'])

@php
    $c = strtolower($country);
@endphp

<span {{ $attributes->merge(['class' => $class]) }} aria-hidden>
    @if($c === 'cz')
        <svg viewBox="0 0 16 12" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block">
            <rect width="16" height="12" fill="#fff" />
            <rect y="6" width="16" height="6" fill="#d7141a" />
            <polygon points="0,0 7.5,6 0,12" fill="#11457e" />
        </svg>
    @elseif($c === 'de')
        <svg viewBox="0 0 16 12" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block">
            <rect width="16" height="4" y="0" fill="#000" />
            <rect width="16" height="4" y="4" fill="#dd0000" />
            <rect width="16" height="4" y="8" fill="#ffce00" />
        </svg>
    @else
        <svg viewBox="0 0 16 12" xmlns="http://www.w3.org/2000/svg" class="w-full h-full block">
            <!-- 13 stripes simplified -->
            <rect width="16" height="12" fill="#b22234" />
            <g fill="#fff">
                @for($i = 0; $i < 6; $i++)
                    <rect x="0" y="{{ $i * 2 }}" width="16" height="1" />
                @endfor
            </g>
            <!-- canton -->
            <rect width="6.8" height="6" x="0" y="0" fill="#3c3b6e" />
            <!-- simplified stars -->
            <g fill="#fff" transform="translate(0.8,0.6)">
                <circle cx="0.6" cy="0.6" r="0.35" />
                <circle cx="1.8" cy="0.6" r="0.35" />
                <circle cx="3.0" cy="0.6" r="0.35" />
                <circle cx="0.6" cy="1.6" r="0.35" />
                <circle cx="1.8" cy="1.6" r="0.35" />
            </g>
        </svg>
    @endif
</span>
