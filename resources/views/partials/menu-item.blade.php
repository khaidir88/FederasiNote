@php
$hasChildren = $menu->children->count() > 0;
$isActive = request()->is($menu->slug . '*');
@endphp

@foreach($children as $parent)
<li class="nav-item dropdown dropdown-mega position-static">
    <a class="nav-link text-gold dropdown-toggle"
        href="{{ route('category.show', $parent->slug) }}"
        id="cat{{ $parent->id }}"
        data-bs-toggle="dropdown">
        {{ $parent->name }}
    </a>

    @if($parent->children->count())
    <div class="dropdown-menu dropdown-mega shadow p-4">
        <div class="row">
            @foreach($parent->children->chunk(4) as $chunk)
            <div class="col-md-3">
                @foreach($chunk as $child)
                <a class="dropdown-item text-gold"
                    href="{{ route('category.show', [$parent->slug, $child->slug]) }}">
                    {{ $child->name }}
                </a>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    @endif
</li>
@endforeach