<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'keterangan',
        'slug',
        'content',
        'image',
        'video_url',
        'author',
        'category_id',
        'views',
        'publish_at',
        'meta_description',
        'status',
        'tags'
    ];


    protected $casts = [
        'publish_at' => 'datetime',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            if (empty($article->user_id) && auth()->check()) {
                $article->user_id = auth()->id();
            }

            if (empty($article->author) && auth()->check()) {
                $article->author = auth()->user()->name;
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Relasi ke komentar
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
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
     * Get excerpt of content
     */
    public function getExcerpt($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }
    /**
     * Scope untuk artikel popular
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
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

    // Untuk form input tags
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
}
