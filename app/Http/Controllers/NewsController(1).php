<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsContent;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::with([
            'category',
            'user',
            'contents' => function ($q) {
                $q->orderBy('order');
            }
        ])->latest();

        // =====================
        // SEARCH
        // =====================
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhereHas('contents', function ($qc) use ($search) {
                        $qc->where('content', 'like', "%{$search}%");
                    });
            });
        }

        // =====================
        // FILTER CATEGORY
        // =====================
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // =====================
        // FILTER STATUS
        // =====================
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $news = $query->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('berita.index', compact('news', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('berita.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'author' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:500',
            'publish_at' => 'nullable|date_format:Y-m-d\TH:i',
            'status' => 'required|in:draft,published,archived',

            'contents' => 'required|array|min:1',
            'contents.*.type' => 'required|in:text,image,video,related',
            'contents.*.position' => 'nullable|integer',

            'contents.*.text' => 'nullable|string',

            'contents.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'contents.*.video' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:102400',
            'contents.*.youtube_url' => 'nullable|url',
            'contents.*.video_orientation' => 'nullable|in:landscape,portrait',

            'contents.*.caption' => 'nullable|string|max:255',

            'contents.*.related_title' => 'nullable|string|max:255',
            'contents.*.related_url' => 'nullable|url',

            'contents.*.video_width'  => 'nullable|integer|min:30|max:100',
            'contents.*.video_align'  => 'nullable|in:left,center,right',
            'contents.*.video_radius' => 'nullable|integer|min:0|max:40',
        ]);

        DB::transaction(function () use ($request, $validated) {

            // ================= TAG =================
            $tags = null;
            if (!empty($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
            }

            // ================= PUBLISH DATE =================
            $publishAt = null;
            if ($validated['status'] === 'published') {
                $publishAt = $validated['publish_at'] ?? now();
            }

            // ================= IMAGE UTAMA =================
            $imageName = null;

            if ($request->hasFile('image')) {

                $image = $request->file('image');

                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('images/articles'), $imageName);
            }

            // ================= CREATE NEWS =================
            $news = News::create([
                'title' => $validated['title'],
                'keterangan' => $validated['keterangan'],
                'category_id' => $validated['category_id'] ?? null,
                'author' => $validated['author'] ?? null,
                'image' => $imageName,
                'meta_description' => $validated['meta_description'] ?? null,
                'tags' => $tags,
                'publish_at' => $publishAt,
                'status' => $validated['status'],
                'user_id' => auth()->id(),
            ]);

            // ================= CONTENT =================
            foreach ($validated['contents'] as $index => $item) {

                $data = [
                    'type' => $item['type'],
                    'position' => $item['position'] ?? ($index + 1),
                ];

                // TEXT
                if ($item['type'] === 'text') {

                    $data['content'] = $item['text'] ?? null;
                }

                // IMAGE
                if ($item['type'] === 'image') {

                    if (isset($item['image'])) {

                        $image = $item['image'];

                        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                        $image->move(public_path('images/articles'), $filename);

                        $data['image_path'] = $filename;
                    }

                    $data['caption'] = $item['caption'] ?? null;
                }

                // VIDEO
                // ================= VIDEO =================
if ($item['type'] === 'video') {

    // Simpan Pengaturan Tampilan Video
    $data['video_orientation'] = $item['video_orientation'] ?? 'landscape';
    $data['video_width']       = $item['video_width'] ?? 100;
    $data['video_align']       = $item['video_align'] ?? 'center';
    $data['video_radius']      = $item['video_radius'] ?? 12;

    // Upload Video File
    if (!empty($item['video']) && $item['video'] instanceof \Illuminate\Http\UploadedFile) {

        $video = $item['video'];
        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
        $video->move(public_path('videos/articles'), $filename);

        $data['video_path']  = $filename;
        $data['youtube_url'] = null; // Reset URL Youtube jika upload file lokal

    } else {
        // Jika tidak ada file baru yang diunggah, simpan URL Youtube
        $data['youtube_url'] = $item['youtube_url'] ?? null;
    }

    // Caption
    $data['caption'] = $item['caption'] ?? null;
}

                // RELATED
                if ($item['type'] === 'related') {

                    $data['related_title'] = $item['related_title'] ?? null;
                    $data['related_url'] = $item['related_url'] ?? null;
                }

                $news->contents()->create($data);
            }
        });

        return redirect()
            ->route('berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(News $news)
    {
        $categories = Category::all();

        // load konten berurutan
        $news->load(['contents' => fn($q) => $q->orderBy('order')]);

        return view('berita.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $news = News::with('contents')->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'keterangan' => 'required|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'author' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:160',
            'tags' => 'nullable|string|max:500',
            'publish_at' => 'nullable|date_format:Y-m-d\TH:i',
            'status' => 'required|in:draft,published,archived',

            'contents' => 'required|array|min:1',
            'contents.*.id' => 'nullable|exists:news_contents,id',
            'contents.*.type' => 'required|in:text,image,video,related',
            'contents.*.position' => 'nullable|integer',

            'contents.*.text' => 'nullable|string',

            'contents.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'contents.*.video' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:102400',
            'contents.*.youtube_url' => 'nullable|url',

            'contents.*.caption' => 'nullable|string|max:255',

            'contents.*.related_title' => 'nullable|string|max:255',
            'contents.*.related_url' => 'nullable|url',


        ]);

        DB::transaction(function () use ($request, $validated, $news) {

            // ================= TAG =================
            $tags = null;
            if (!empty($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
            }

            // ================= PUBLISH DATE =================
            $publishAt = null;
            if ($validated['status'] === 'published') {
                $publishAt = $validated['publish_at'] ?? now();
            }

            // ================= IMAGE UTAMA =================
            $imageName = $news->image;

            if ($request->hasFile('image')) {

                if ($news->image && file_exists(public_path('images/articles/' . $news->image))) {
                    unlink(public_path('images/articles/' . $news->image));
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/articles'), $imageName);
            }

            // ================= UPDATE NEWS =================
            $news->update([
                'title' => $validated['title'],
                'keterangan' => $validated['keterangan'],
                'category_id' => $validated['category_id'] ?? null,
                'author' => $validated['author'] ?? null,
                'image' => $imageName,
                'meta_description' => $validated['meta_description'] ?? null,
                'tags' => $tags,
                'publish_at' => $publishAt,
                'status' => $validated['status'],
            ]);

            // ================= CONTENT =================
            $existingIds = $news->contents->pluck('id')->toArray();
            $requestIds = collect($validated['contents'])->pluck('id')->filter()->toArray();

            $deleteIds = array_diff($existingIds, $requestIds);

            foreach ($deleteIds as $deleteId) {
                $content = $news->contents()->find($deleteId);

                if ($content) {

                    if ($content->image_path && file_exists(public_path('images/articles/' . $content->image_path))) {
                        unlink(public_path('images/articles/' . $content->image_path));
                    }

                    if ($content->video_path && file_exists(public_path('videos/articles/' . $content->video_path))) {
                        unlink(public_path('videos/articles/' . $content->video_path));
                    }

                    $content->delete();
                }
            }

            foreach ($validated['contents'] as $index => $item) {

                $data = [
                    'type' => $item['type'],
                    'position' => $item['position'] ?? ($index + 1),
                ];

                // TEXT
                if ($item['type'] === 'text') {
                    $data['content'] = $item['text'] ?? null;
                }

                // IMAGE
                if ($item['type'] === 'image') {

                    if (isset($item['image'])) {

                        $image = $item['image'];

                        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                        $image->move(public_path('images/articles'), $filename);

                        $data['image_path'] = $filename;
                    }

                    $data['caption'] = $item['caption'] ?? null;
                }

                // VIDEO
                if ($item['type'] === 'video') {

                    if (isset($item['video'])) {

                        $video = $item['video'];

                        $filename = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();

                        $video->move(public_path('videos/articles'), $filename);

                        $data['video_path'] = $filename;

                        $data['youtube_url'] = null;
                    } else {

                        $data['youtube_url'] = $item['youtube_url'] ?? null;
                    }

                    $data['caption'] = $item['caption'] ?? null;
                }

                // RELATED
                if ($item['type'] === 'related') {

                    $data['related_title'] = $item['related_title'] ?? null;
                    $data['related_url'] = $item['related_url'] ?? null;
                }

                if (!empty($item['id'])) {

                    $news->contents()->where('id', $item['id'])->update($data);
                } else {

                    $news->contents()->create($data);
                }
            }
        });

        return redirect()
            ->route('berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }


    public function show($slug)
    {
        $query = News::with([
            'category',
            'contents' => fn($q) => $q->orderBy('order'),
            'comments' => fn($q) => $q->where('approved', true)->latest()
        ])->where('slug', $slug);

        // Jika bukan admin → hanya boleh lihat published
        if (!auth()->check()) {
            $query->where('status', 'published')
                ->where('publish_at', '<=', now());
        }

        $news = $query->firstOrFail();

        // View hanya dihitung jika published & publik
        if ($news->status === 'published' && $news->publish_at <= now()) {
            $news->increment('views');
        }

        // ======================
        // BERITA POPULER
        // ======================
        $popularArticles = News::where('id', '!=', $news->id)
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // ======================
        // BERITA TERKAIT
        // ======================
        $relatedArticles = News::where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // ======================
        // KATEGORI
        // ======================
        $categories = Category::withCount([
            'news' => fn($q) => $q->where('status', 'published')
        ])->get();

        return view('news.show', compact(
            'news',
            'popularArticles',
            'relatedArticles',
            'categories'
        ));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)  // Ubah dari News $news menjadi $id
    {
        // Cari berita berdasarkan ID
        $news = News::findOrFail($id);

        // Delete image if exists
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Force delete (permanent delete)
     */
    public function forceDelete($id)
    {
        $news = News::withTrashed()->findOrFail($id);

        // Delete image if exists
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        $news->forceDelete();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dihapus permanen.');
    }

    /**
     * Restore soft deleted news
     */
    public function restore($id)
    {
        $news = News::withTrashed()->findOrFail($id);
        $news->restore();

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dipulihkan.');
    }

    /**
     * Publish news
     */
    public function publish(News $news)
    {
        $news->update([
            'status' => 'published',
            'publish_at' => now()
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dipublikasikan.');
    }

    /**
     * Unpublish news (set to draft)
     */
    public function unpublish(News $news)
    {
        $news->update([
            'status' => 'draft',
            'publish_at' => null
        ]);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil dijadikan draft.');
    }

    /**
     * Archive news
     */
    public function archive(News $news)
    {
        $news->update(['status' => 'archived']);

        return redirect()->route('berita.index')
            ->with('success', 'Berita berhasil diarsipkan.');
    }
}
