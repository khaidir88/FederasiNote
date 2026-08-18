<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Article;
use App\Models\News;
use Illuminate\Support\Carbon;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function home()
    {
        $publishedArticles = News::with(['category', 'contents'])
            ->where('status', 'published')
            ->where('publish_at', '<=', now())
            ->orderBy('publish_at', 'desc')
            ->get();

        $trendingArticles = News::with(['category', 'contents'])
            ->where('status', 'published')
            ->where('publish_at', '<=', now())
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();

        // Hanya ambil artikel yang statusnya published
        // $publishedArticles = News::where('status', 'published')
        //     ->where('publish_at', '<=', now())
        //     ->orderBy('publish_at', 'desc')
        //     ->with('category')
        //     ->get();

        // Artikel trending (berdasa)
        // $trendingArticles = News::where('status', 'published')
        //     ->where('publish_at', '<=', now())
        //     ->orderBy('views', 'desc')
        //     ->take(6)
        //     ->with('category')
        //     ->get();

        $categories = Category::withCount(['news' => function ($query) {
            $query->where('status', 'published')
                ->where('publish_at', '<=', now());
        }])->get();

        return view('home', compact('publishedArticles', 'trendingArticles', 'categories'));
    }

    public function index(Request $request)
    {
        $query = News::with('category')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc');

        // Filter kategori
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter tag
        if ($request->has('tag')) {
            $query->where('tags', 'like', '%' . $request->tag . '%');
        }

        // ganti nama variable agar konsisten
        $news = $query->paginate(10);

        $categories = Category::withCount(['news' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        return view('news.index', compact('news', 'categories', 'request'));
    }

    public function show($slug)
    {
        $news = News::with([
            'category',
            'comments' => function ($q) {
                $q->where('approved', true)
                    ->orderBy('created_at', 'desc');
            }
        ])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // tambah view
        $news->increment('views');

        // berita populer
        $popularArticles = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // berita terkait
        $relatedArticles = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->where('category_id', $news->category_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // kategori count berdasarkan news
        $categories = Category::withCount(['news' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        return view('news.show', compact(
            'news',
            'popularArticles',
            'relatedArticles',
            'categories'
        ));
    }
}
