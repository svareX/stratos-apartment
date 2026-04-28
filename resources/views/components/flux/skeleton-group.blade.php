@props (['cols' => 3, 'rows' => 1, 'gap' => '2'])
@php
    $colsClass = is_numeric($cols) ? "grid-cols-{$cols}" : $cols;
    $gapClass = $gap ? "gap-{$gap}" : 'gap-2';
@endphp

<div class="flux-skeleton-group grid {{ $colsClass }} {{ $gapClass }}">
    @for ($r = 0; $r < $rows; $r++)
        @for ($c = 0; $c < $cols; $c++)
            <div class="flux-skeleton h-36 w-full rounded"></div>
        @endfor
    @endfor
</div>
