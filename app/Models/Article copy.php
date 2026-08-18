<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Article extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'author',
        'image',
        'image_caption',
        'video_url', // ✅ tambahkan ini
        'meta_description',
        'tags',
        'publish_at',
        'status',
    ];


    protected $dates = [
        'publish_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Boot function untuk generate slug otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            if (empty($article->image_caption)) {
                $article->image_caption = 'Sumber: Dok. Pribadi';
            }

            if (empty($article->user_id) && auth()->check()) {
                $article->user_id = auth()->id();
            }
        });

        // Jangan set caption saat updating!
    }



    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getPublishedDateAttribute()
    {
        if (empty($this->published_at)) {
            return 'Belum dipublikasikan';
        }

        try {
            return date('d M Y', strtotime($this->published_at));
        } catch (\Exception $e) {
            return 'Format tanggal invalid';
        }
    }

    public function getAuthorAttribute()
    {
        // Jika ada user relationship, gunakan user name
        if ($this->user_id && $this->relationLoaded('user') && $this->user) {
            return $this->user->name;
        }

        // Fallback ke field author di database jika ada
        if (isset($this->attributes['author']) && !empty($this->attributes['author'])) {
            return $this->attributes['author'];
        }

        return 'Unknown Author';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Relasi ke komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * Get approved comments
     */
    public function approvedComments()
    {
        return $this->comments()->where('approved', true);
    }

    /**
     * Increment view count
     */
    public function incrementViewsCount()
    {
        $this->views++;
        return $this->save();
    }

    /**
     * Get comments count attribute
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->where('approved', true)->count();
    }

    /**
     * Scope untuk artikel yang published
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    // Helper methods
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at <= now();
    }

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Scope untuk artikel popular
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    /**
     * Get excerpt of content
     */
    public function getExcerpt($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    // Accessor untuk tags
    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }
}
