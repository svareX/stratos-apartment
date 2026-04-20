@props([
    'path',
    'alt' => '',
    'widths' => [400, 800, 1200],
    'sizes' => '100vw',
    'loading' => 'lazy',
])

@php
    $widths = is_array($widths) ? $widths : array_map('intval', explode(',', $widths));
    sort($widths);
    $max = end($widths) ?: 800;
    $base = url('/img');
    $src = $base . '?path=' . urlencode($path) . '&w=' . $max;
    $srcset = collect($widths)->map(fn($w) => $base . '?path=' . urlencode($path) . '&w=' . $w . ' ' . $w . 'w')->join(', ');
@endphp

<img src="{{ $src }}"
     srcset="{{ $srcset }}"
     sizes="{{ $sizes }}"
     alt="{{ $alt }}"
     loading="{{ $loading }}"
     decoding="async"
     {{ $attributes }} />
