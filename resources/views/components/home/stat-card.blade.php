
<div class="stat-card bg-white11 shadow-sm ">
    
    <div class="inner {{isset($icon)?'d-flex align-items-center':''}}">
        @if($icon)
            <div class="icon">
                <img src="{{$icon}}">
            </div>
        @endif
        <div>
            <p class="stat-card-label">{{ $label }}</p>
            <h3 class="stat-card-value">{{ $value }}</h3>
        </div>

        
    </div>

</div>
