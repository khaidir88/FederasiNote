<nav>
    <a href="/{{ $parent->slug }}">{{ $parent->name }}</a>
    › {{ $category->name }}
</nav>

<h2>{{ $category->name }}</h2>
<h1> tes</h1>

@foreach($news as $item)
<h5>{{ $item->title }}</h5>
@endforeach

{{ $news->links() }}