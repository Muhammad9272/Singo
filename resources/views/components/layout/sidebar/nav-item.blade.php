@props(['name', 'route', 'icon' => 'fas fa-grip-horizontal'])


<li class="nav-item">
    <a href="{{ route($route) }}" class="nav-link {{ navActiveClass($route) }}">
        <i class="{{ $icon }} nav-icon"></i>
        <p>{{ $name }}</p>
    </a>
</li>
