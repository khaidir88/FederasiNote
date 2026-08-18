@php
$hasChildren = $menu->children->count() > 0;
$isActive = request()->is($menu->slug . '*');
@endphp

<li class="nav-item {{ $hasChildren ? 'dropdown' : '' }}">
    <a
        class="nav-link text-gold {{ $hasChildren ? 'dropdown-toggle' : '' }} {{ $isActive ? 'active' : '' }}"
        href="{{ $hasChildren ? '#' : ($menu->url ?? url($menu->slug)) }}"
        @if($hasChildren)
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        @endif>
        @if($menu->icon)
        <i class="{{ $menu->icon }} nav-icon"></i>
        @endif
        {{ $menu->name }}
    </a>

    @if($hasChildren)
    <ul class="dropdown-menu">
        @foreach($menu->children as $child)
        @include('partials.menu-item', ['menu' => $child])
        @endforeach
    </ul>
    @endif
</li>