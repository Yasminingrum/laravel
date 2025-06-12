@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'disabled' => false
])

@php
    $classes = 'btn btn-' . $variant;

    switch($size) {
        case 'sm':
            $classes .= ' btn-sm';
            break;
        case 'lg':
            $classes .= ' btn-lg';
            break;
    }

    if($disabled) {
        $classes .= ' disabled';
    }
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" {{ $attributes }}>
        @if($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot ?? '' }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        @if($icon)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $slot ?? '' }}
    </button>
@endif
