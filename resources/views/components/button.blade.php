@props(['href' => '#', 'type' => 'primary', 'size' => 'md'])

@php
    $classes = 'btn btn-' . $type;
    if ($size === 'sm') {
        $classes .= ' btn-sm';
    } elseif ($size === 'lg') {
        $classes .= ' btn-lg';
    }
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
