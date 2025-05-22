@props(['type' => 'info', 'dismissible' => true, 'icon' => true])

@php
    $alertClass = 'alert alert-' . $type;
    if ($dismissible) {
        $alertClass .= ' alert-dismissible fade show';
    }

    $icons = [
        'success' => 'bi bi-check-circle-fill',
        'danger' => 'bi bi-exclamation-triangle-fill',
        'warning' => 'bi bi-exclamation-triangle-fill',
        'info' => 'bi bi-info-circle-fill',
        'primary' => 'bi bi-info-circle-fill',
        'secondary' => 'bi bi-info-circle-fill',
        'light' => 'bi bi-info-circle-fill',
        'dark' => 'bi bi-info-circle-fill'
    ];

    $iconClass = $icons[$type] ?? $icons['info'];
@endphp

<div {{ $attributes->merge(['class' => $alertClass, 'role' => 'alert']) }}>
    @if ($icon)
        <i class="{{ $iconClass }} me-2"></i>
    @endif

    {{ $slot }}

    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
