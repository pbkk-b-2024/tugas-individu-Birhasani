<li class="nav-item">
    <a href="{{ $href }}" class="nav-link {{ $active ? 'active' : '' }}">
        <i class="nav-icon {{ $icon }}"></i>
        <p>{{ $slot }}</p>
    </a>
</li>
