<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user(); // pastikan import Auth

            // Jika belum login
            if (!$user) {
                return redirect()->route('login');
            }

            // Batasi role yang bisa akses dashboard
            if (!$user->hasAnyRole(['Super Admin', 'Admin', 'Author', 'User'])) {
                session()->flash('akses_ditolak', true);
                return redirect()->route('dashboard'); // bisa arahkan ke halaman lain jika mau
            }

            return $next($request);
        });
    }

    /**
     * Share pending comments count to all dashboard views
     */
    private function getPendingCommentsCount()
    {
        return Comment::where('approved', false)->count();
    }

    /**
     * Display dashboard overview
     */
    public function index()
    {

        // Total counts
        $totalArticles = News::count();
        $totalCategories = Category::count();
        $totalComments = Comment::count();
        $totalUsers = User::count();
        $pendingCommentsCount = $this->getPendingCommentsCount();

        // Recent articles
        $recentArticles = News::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent comments
        $recentComments = Comment::with('news')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Popular articles (by views)
        $popularArticles = News::with('category')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Articles by category (for chart)
        $articlesByCategory = Category::withCount('news')
            ->having('news_count', '>', 0)
            ->orderBy('news_count', 'desc')
            ->get();

        // Monthly article statistics
        $monthlyStats = News::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalArticles',
            'totalCategories',
            'totalComments',
            'totalUsers',
            'pendingCommentsCount',
            'recentArticles',
            'recentComments',
            'popularArticles',
            'articlesByCategory',
            'monthlyStats'
        ));
    }

    /**
     * Display articles management page
     */
    public function news(Request $request)
    {
        $query = News::with('category');

        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'published') {
                $query->where('publish_at', '<=', now())->whereNotNull('publish_at');
            } elseif ($request->status == 'draft') {
                $query->where(function ($q) {
                    $q->where('publish_at', '>', now())->orWhereNull('publish_at');
                });
            }
        }

        $news = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('news.index', compact('news', 'categories'));
    }

    /**
     * Show the form for editing a category
     */
    public function editCategory(Category $category)
    {
        $pendingCommentsCount = $this->getPendingCommentsCount();
        return view('category.edit', compact('category', 'pendingCommentsCount'));
    }

    /**
     * Display comments management page
     */
    public function comments(Request $request)
    {
        $query = Comment::with('article')->latest();

        // Filter by approval status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'pending') {
                $query->where('approved', false);
            } elseif ($request->status == 'approved') {
                $query->where('approved', true);
            }
        }

        $comments = $query->paginate(15);
        $pendingCount = Comment::where('approved', false)->count();
        $pendingCommentsCount = $this->getPendingCommentsCount();

        return view('comments.index', compact('comments', 'pendingCount', 'pendingCommentsCount'));
    }

    /**
     * Approve a comment
     */
    public function approveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['approved' => true]);

        return redirect()->back()->with('success', 'Komentar berhasil disetujui.');
    }
    public function unapproveComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approved = false;
        $comment->save();

        return back()->with('success', 'Komentar berhasil dibatalkan approve.');
    }
    /**
     * Reject/delete a comment
     */
    public function rejectComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }

    /**
     * Display users management page
     */
    public function users()
    {
        $users = User::paginate(10);
        $pendingCommentsCount = $this->getPendingCommentsCount();

        return view('users.index', compact('users', 'pendingCommentsCount'));
    }

    /**
     * Display analytics page
     */
    public function analytics()
    {
        // Top viewed articles
        $topViewedArticles = News::orderBy('views', 'desc')->take(10)->get();

        // Comments statistics
        $commentsStats = [
            'total' => Comment::count(),
            'approved' => Comment::where('approved', true)->count(),
            'pending' => Comment::where('approved', false)->count(),
        ];

        // Articles statistics by month (last 6 months)
        $monthlyArticles = News::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Categories with article counts
        $categoryStats = Category::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->get();

        $pendingCommentsCount = $this->getPendingCommentsCount();

        return view('analytics.index', compact(
            'topViewedArticles',
            'commentsStats',
            'monthlyArticles',
            'categoryStats',
            'pendingCommentsCount'
        ));
    }

    /**
     * Store a newly created article in storage.
     */
    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
        ]);
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('images/articles'), $filename);
                $validated['image'] = $filename;
            }

            $validated['slug'] = Str::slug($validated['title']);

            if ($request->has('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $validated['tags'] = json_encode($tags);
            }

            News::create($validated);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil dibuat!');
        } catch (\Exception $e) {
            if (isset($fileName)) {
                Storage::disk('public')->delete('images/articles/' . $fileName);
            }
            return back()->withInput()
                ->with('error', 'Gagal menambahkan Artikel: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new article.
     */
    public function createArticle()
    {
        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function editArticle(Article $article)
    {
        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified article in storage.
     */
    public function updateArticle(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_at' => 'nullable|date',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string',
        ]);

        try {
            // Cari artikel yang akan diupdate
            $article = Article::findOrFail($id);

            // Handle image upload jika ada gambar baru
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($article->image && file_exists(public_path('images/articles/' . $article->image))) {
                    unlink(public_path('images/articles/' . $article->image));
                }

                // Upload gambar baru
                $filename = time() . '_' . $request->file('image')->getClientOriginalName();
                $request->image->move(public_path('images/articles'), $filename);
                $validated['image'] = $filename;
            } else {
                // Pertahankan gambar lama jika tidak ada gambar baru
                $validated['image'] = $article->image;
            }

            // Generate slug baru jika judul berubah
            if ($article->title !== $validated['title']) {
                $validated['slug'] = Str::slug($validated['title']);
            } else {
                $validated['slug'] = $article->slug;
            }

            // Handle tags
            if ($request->has('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $validated['tags'] = json_encode($tags);
            } else {
                $validated['tags'] = $article->tags;
            }

            // Update artikel
            $article->update($validated);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil diperbarui!');
        } catch (\Exception $e) {
            // Hapus gambar baru yang sudah diupload jika terjadi error
            if (isset($filename) && file_exists(public_path('images/articles/' . $filename))) {
                unlink(public_path('images/articles/' . $filename));
            }

            return back()->withInput()
                ->with('error', 'Gagal memperbarui artikel: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified article from storage.
     */
    public function destroyArticle(Article $article)
    {
        // Hapus gambar jika ada
        if ($article->image) {
            Storage::delete('public/articles/' . $article->image);
        }

        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    public function show($slug)
    {
        // Ambil artikel dengan relasi
        $article = Article::with(['category', 'comments' => function ($query) {
            $query->where('approved', true)->orderBy('created_at', 'desc');
        }])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $article->increment('views');

        // Artikel popular (excluding current article)
        $popularArticles = Article::where('id', '!=', $article->id)
            ->published()
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Artikel terkait (same category)
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->published()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Kategori dengan count
        $categories = Category::withCount(['articles' => function ($query) {
            $query->published();
        }])->get();

        return view('articles.show', compact(
            'article',
            'popularArticles',
            'relatedArticles',
            'categories'
        ));
    }

    // public function publish(Article $article)
    // {
    //     try {
    //         $article->update([
    //             'status' => 'published',
    //             'published_at' => now()
    //         ]);

    //         return redirect()->route('articles.index')
    //             ->with('success', 'Artikel berhasil dipublish');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Gagal mempublish artikel: ' . $e->getMessage());
    //     }
    // }

    // public function unpublish(Article $article)
    // {
    //     try {
    //         $article->update([
    //             'status' => 'draft'
    //         ]);

    //         return redirect()->route('articles.index')
    //             ->with('success', 'Artikel berhasil dijadikan draft');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Gagal mengubah status artikel: ' . $e->getMessage());
    //     }
    // }

    public function publish(Article $article)
    {
        try {
            // Debug: Cek data sebelum update
            \Log::info('Publishing article:', [
                'article_id' => $article->id,
                'current_status' => $article->status,
                'current_published_at' => $article->published_at
            ]);

            $article->update([
                'status' => 'published',
                'published_at' => now()
            ]);

            // Debug: Cek data setelah update
            \Log::info('Article published successfully:', [
                'article_id' => $article->id,
                'new_status' => $article->fresh()->status,
                'new_published_at' => $article->fresh()->published_at
            ]);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil dipublish');
        } catch (\Exception $e) {
            \Log::error('Error publishing article:', [
                'article_id' => $article->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mempublish artikel: ' . $e->getMessage());
        }
    }

    public function unpublish(Article $article)
    {
        try {
            $article->update([
                'status' => 'draft'
                // published_at tetap dipertahankan
            ]);

            return redirect()->route('articles.index')
                ->with('success', 'Artikel berhasil dijadikan draft');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status artikel: ' . $e->getMessage());
        }
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        $pendingCommentsCount = $this->getPendingCommentsCount();
        return view('settings.index', compact('pendingCommentsCount'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'posts_per_page' => 'required|integer|min:1|max:100',
            'comment_approval' => 'required|boolean',
        ]);

        // Untuk sementara, simpan di session atau database simple
        session([
            'site_settings' => [
                'site_name' => $request->site_name,
                'site_description' => $request->site_description,
                'posts_per_page' => $request->posts_per_page,
                'comment_approval' => $request->comment_approval,
            ]
        ]);

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats()
    {
        $stats = [
            'articles' => Article::count(),
            'categories' => Category::count(),
            'comments' => Comment::count(),
            'pending_comments' => Comment::where('approved', false)->count(),
        ];

        return response()->json($stats);
    }


    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,author,user',
            'is_active' => 'boolean'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->is_active ?? true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,author,user',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
