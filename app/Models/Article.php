<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'keterangan',
        'content',
        'category_id',
        'author',
        'image',
        'video_url',
        'status',
        'publish_at',
        'meta_description',
        'tags',
        'user_id',
        'slug'
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
    public function getTagsStringAttribute()
    {
        if (!$this->tags) return '';

        if (is_array($this->tags)) {
            return implode(', ', $this->tags);
        }

        return $this->tags;
    }
}
