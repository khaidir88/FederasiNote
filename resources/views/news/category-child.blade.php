@extends('layouts.guest')

@section('title', $category->name)

@section('content')
<div class="container py-5">

    {{-- ================= Breadcrumb ================= --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('news.index') }}">
                    {{ $parent->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $category->name }}
            </li>
        </ol>
    </nav>

    {{-- ================= Header ================= --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-2">
                {{ $category->name }}
            </h2>
            <p class="text-muted">
                Berita dan artikel seputar {{ strtolower($category->name) }}
            </p>
        </div>
    </div>

    <div class="row">
        {{-- ================= Main Content ================= --}}
        <div class="col-lg-8">

            @forelse($articles as $article)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4">
                        <a href="{{ route('news.show', $article->slug) }}">
                            <img src="{{ asset('public/../images/articles/'.$article->image) }}"
                                class="img-fluid rounded-start w-100"
                                style="object-fit: cover; min-height: 200px;">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge"
                                    style="background-color: {{ $article->category->color ?? '#6c757d' }}; color:white;">
                                    {{ $article->category->name ?? 'Uncategorized' }}
                                </span>


                                <small class="text-muted">
                                    {{ $article->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <h5 class="fw-bold">
                                <a href="{{ route('news.show', $article->slug) }}"
                                    class="text-dark text-decoration-none">
                                    {{ $article->title }}
                                </a>
                            </h5>

                            <p class="text-muted">
                                {{ Str::limit(strip_tags($article->content), 150) }}
                            </p>

                            <small class="text-muted">
                                <i class="bi bi-eye"></i> {{ $article->views ?? 0 }}x dilihat
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-newspaper display-4 text-muted"></i>
                <h4 class="mt-3">Belum ada berita</h4>
                <p class="text-muted">
                    Belum ada berita pada kategori {{ $category->name }}
                </p>
            </div>
            @endforelse

            {{-- Show More --}}
            @if(!request()->has('show') && $articles->count() >= 3)
            <div class="text-center mt-4">
                <a href="{{ request()->fullUrlWithQuery(['show' => 'all']) }}"
                    class="btn btn-outline-primary px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-arrow-down-circle me-1"></i>
                    Show More
                </a>
            </div>
            @endif

            {{-- Show Less --}}
            @if(request('show') === 'all')
            <div class="text-center mt-4">
                <a href="{{ url()->current() }}"
                    class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-arrow-up-circle me-1"></i>
                    Show Less
                </a>
            </div>
            @endif


        </div>

        {{-- ================= Sidebar ================= --}}
        <div class="col-lg-4">

            {{-- Sub Categories --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-diagram-3 me-2"></i>
                        {{ $parent->name }}
                    </h5>

                    <div class="list-group list-group-flush">
                        @foreach($parent->children as $child)
                        <a href="{{ url('news/'.$parent->slug.'/'.$child->slug) }}"
                            class="list-group-item list-group-item-action
                                {{ $child->id == $category->id ? 'active' : '' }}">
                            {{ $child->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.7rem;
        font-weight: 500;
    }

    .color-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
    }

    /* Style untuk badge kategori di artikel */
    .card .badge {
        font-size: 0.65rem;
        padding: 0.35em 0.65em;
    }

    /* Style untuk badge di sidebar kategori */
    .list-group-item .badge {
        font-size: 0.65rem;
        padding: 0.3em 0.6em;
    }
</style>
@endpush