@props(['type' => 'info', 'message', 'dismissible' => true])

<div class="container mt-3">
    <div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert">
        @if($type === 'success')
            <i class="fas fa-check-circle me-2"></i>
        @elseif($type === 'danger' || $type === 'error')
            <i class="fas fa-exclamation-triangle me-2"></i>
        @elseif($type === 'warning')
            <i class="fas fa-exclamation-circle me-2"></i>
        @else
            <i class="fas fa-info-circle me-2"></i>
        @endif

        {{ $message }}

        @if($dismissible)
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        @endif
    </div>
</div>
