<div class="stat-card bg-white shadow-sm">
    <div class="inner">
        <h3 class="stat-card-value">{{ $value }}</h3>
        <p class="stat-card-label">{{ $label }}</p>
    </div>
    @if($icon)
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
    @endif
</div>
