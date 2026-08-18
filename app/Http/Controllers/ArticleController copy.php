<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class ArticleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (!auth()->user()->hasAnyRole(['Admin', 'Super Admin'])) {
    //             session()->flash('akses_ditolak', true);
    //             return redirect()->route('dashboard');
    //         }
    //         return $next($request);
    //     });
    // }
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->hasAnyRole(['Admin', 'Super Admin'])) {
                session()->flash('akses_ditolak', true);
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    /** ===============================
     *  INDEX
     *  =============================== */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::with('category', 'user')->latest();

        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $news = $query->paginate(15);
        $categories = Category::all();

        return view('articles.index', compact('articles', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'author' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:500',
            'publish_at' => 'nullable|date_format:Y-m-d\TH:i',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Handle tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = $tags;
        } else {
            $validated['tags'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Buat nama file unik
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();

            // Pindahkan file ke public/images/articles/
            $request->image->move(public_path('images/articles'), $imageName);

            // Simpan path relatif
            $validated['image'] = 'images/articles/' . $imageName;
        }

        // Set publish_at based on status
        if ($validated['status'] === 'published') {
            if (empty($validated['publish_at'])) {
                $validated['publish_at'] = now();
            }
        } else {
            $validated['publish_at'] = null;
        }

        // Set user_id from auth
        $validated['user_id'] = auth()->id();

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'articles berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Article $article)
    public function show($slug)
    {
        $article = Article::with(['category', 'comments' => function ($q) {
            $q->where('approved', true)->latest();
        }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Hitung views
        $article->increment('views');

        // Artikel populer & terkait
        $popularArticles = Article::where('id', '!=', $article->id)
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        $categories = Category::withCount(['articles' => fn($q) => $q->where('status', 'published')])->get();

        return view('news.show', compact('article', 'popularArticles', 'relatedArticles', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $articles = Article::findOrFail($id);
        $categories = Category::all();
        return view('articles.edit', compact('articles', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $articles = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'content' => 'required',
            'meta_description' => 'nullable|string|max:160',
            'category_id' => 'nullable|exists:categories,id',
            'author' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url',
            'tags' => 'nullable|string|max:500',
            'publish_at' => 'nullable|date_format:Y-m-d\TH:i',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Handle tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = $tags;
        } else {
            $validated['tags'] = null;
        }

        // Handle image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($articles->image) {
                Storage::disk('public')->delete($articles->image);
            }

            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
        } elseif ($request->has('remove_image') && $request->remove_image == '1') {
            // Remove image
            if ($articles->image) {
                Storage::disk('public')->delete($articles->image);
            }
            $validated['image'] = null;
        } else {
            // Keep existing image
            unset($validated['image']);
        }

        // Set publish_at based on status
        if ($validated['status'] === 'published') {
            if (empty($validated['publish_at'])) {
                $validated['publish_at'] = now();
            }
        } else {
            $validated['publish_at'] = null;
        }

        $articles->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'articles berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)  // Ubah dari News $news menjadi $id
    {
        // Cari articles berdasarkan ID
        $articles = Article::findOrFail($id);

        // Delete image if exists
        if ($articles->image) {
            Storage::disk('public')->delete($articles->image);
        }

        $articles->delete();

        return redirect()->route('articles.index')
            ->with('success', 'articles berhasil dihapus.');
    }

    /**
     * Force delete (permanent delete)
     */
    public function forceDelete($id)
    {
        $articles = Article::withTrashed()->findOrFail($id);

        // Delete image if exists
        if ($articles->image) {
            Storage::disk('public')->delete($articles->image);
        }

        $articles->forceDelete();

        return redirect()->route('articles.index')
            ->with('success', 'Berita berhasil dihapus permanen.');
    }

    /**
     * Restore soft deleted news
     */
    public function restore($id)
    {
        $articles = Article::withTrashed()->findOrFail($id);
        $articles->restore();

        return redirect()->route('articles.index')
            ->with('success', 'Berita berhasil dipulihkan.');
    }

    /**
     * Publish news
     */
    public function publish(Article $articles)
    {
        $articles->update([
            'status' => 'published',
            'publish_at' => now()
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'Berita berhasil dipublikasikan.');
    }

    /**
     * Unpublish news (set to draft)
     */
    public function unpublish(Article $articles)
    {
        $articles->update([
            'status' => 'draft',
            'publish_at' => null
        ]);

        return redirect()->route('articles.index')
            ->with('success', 'articles berhasil dijadikan draft.');
    }

    /**
     * Archive news
     */
    public function archive(Article $articles)
    {
        $articles->update(['status' => 'archived']);

        return redirect()->route('articles.index')
            ->with('success', 'articles berhasil diarsipkan.');
    }
}
