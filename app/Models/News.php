<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\NewsContent;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'keterangan',
        'slug',
        'image',
        'video_url',
        'youtube_url',
        'related_title',
        'related_url',
        'position',
        'author',
        'category_id',
        'views',
        'publish_at',
        'meta_description',
        'status',
        'tags',

    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'tags' => 'array',
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = self::generateUniqueSlug($news->title);
            }

            if (empty($news->user_id) && auth()->check()) {
                $news->user_id = auth()->id();
            }

            if (empty($news->author) && auth()->check()) {
                $news->author = auth()->user()->name;
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title')) {
                $news->slug = self::generateUniqueSlug($news->title, $news->id);
            }
        });
    }
    /* =========================
     |  SLUG HELPER
     ========================= */
    protected static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (
            self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('publish_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'published')
            ->where('publish_at', '>', now());
    }

    // Accessors
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getFormattedPublishAtAttribute()
    {
        return $this->publish_at ? $this->publish_at->format('d M Y H:i') : 'Not scheduled';
    }

    public function getTagsListAttribute()
    {
        return $this->tags ? implode(', ', $this->tags) : '';
    }

    // Methods
    public function isPublished()
    {
        return $this->status === 'published' && $this->publish_at <= now();
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isScheduled()
    {
        return $this->status === 'published' && $this->publish_at > now();
    }

    public function isArchived()
    {
        return $this->status === 'archived';
    }

    public function incrementViews()
    {
        $this->timestamps = false;
        $this->increment('views');
        $this->timestamps = true;
    }


    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }

        if (is_array($this->tags)) {
            return $this->tags;
        }

        // Jika string, coba decode JSON
        $decoded = json_decode($this->tags, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        // Jika string dengan koma, split
        if (is_string($this->tags) && str_contains($this->tags, ',')) {
            return array_map('trim', explode(',', $this->tags));
        }

        // Jika string tunggal
        return [$this->tags];
    }
    public function getTagsStringAttribute()
    {
        $tagsArray = $this->tags_array;

        if (empty($tagsArray)) {
            return '';
        }

        return implode(', ', $tagsArray);
    }

    /**
     * Relasi ke komentar
     */

    public function comments()
    {
        return $this->hasMany(Comment::class, 'news_id');
    }


    /**
     * Get approved comments
     */

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('approved', 1);
    }

    /**
     * Get comments count attribute
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->where('approved', true)->count();
    }

    public function contents()
    {
        return $this->hasMany(NewsContent::class)->orderBy('position');
    }
}
