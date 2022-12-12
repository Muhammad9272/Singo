@props(['uname','name', 'route', 'icon' => 'fas fa-grip-horizontal'])

@if(isset($uname))
<li class="nav-item">
    <a href="{{ route($route) }}" class="nav-link {{ navActiveClass($route) }}">
        <img class="nav-icon" src="{{ $icon }}">
        {{-- <i class="{{ $icon }} nav-icon"></i> --}}
        <p>{{ $name }}</p>
    </a>
</li>
@else
<li class="nav-item">
    <a href="{{ route($route) }}" class="nav-link {{ navActiveClass($route) }}">
       {{--  <img class="nav-icon" src="{{ $icon }}"> --}}
        <i class="{{ $icon }} nav-icon"></i>
        <p>{{ $name }}</p>
    </a>
</li>
@endif
