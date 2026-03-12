<div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
    <h5 class="mb-0 fw-bold text-dark">
        <i class="fe fe-layers me-1 text-primary"></i> {{ $label }}
    </h5>

    <div class="d-flex gap-2 mt-3">
        <!-- Add Button -->
        <a href="{{ route($route) }}"
           class="btn btn-primary btn-sm px-3 shadow-sm d-flex align-items-center gap-1">
            <i class="{{ $icon }}"></i>
            <span>{{ $buttonText }}</span>
        </a>

        <!-- Trashed Button -->
    @if(isset($routeTrashed) && $routeTrashed != null)
        <a href="{{ route($routeTrashed) }}"
           class="btn btn-outline-danger btn-sm px-3 shadow-sm d-flex align-items-center gap-1">
            <i class="fe fe-trash-2"></i>
            <span>Trashed</span>
        </a>
    @endif
    </div>
</div>
