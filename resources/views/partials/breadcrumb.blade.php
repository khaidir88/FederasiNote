@isset($breadcrumbs)
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-3">
        @foreach($breadcrumbs as $breadcrumb)
        @if($loop->last)
        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
        @else
        <li class="breadcrumb-item">
            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
        </li>
        @endif
        @endforeach
    </ol>
</nav>
@endisset