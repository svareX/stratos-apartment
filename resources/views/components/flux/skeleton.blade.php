@props (['width' => 'full', 'height' => '48', 'rounded' => true])
@php
    $w = $width === 'full' ? 'w-full' : ($width);
    $h = str_contains($height, 'px') ? $height : ($height ? "h-[{$height}px]" : 'h-12');
    $r = $rounded ? 'rounded' : '';
@endphp

<div
    {{ $attributes->merge(['class' => "flux-skeleton {$r} {$w} {$h}"]) }}
></div>
