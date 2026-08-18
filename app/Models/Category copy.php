<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'color',
        'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getLevelAttribute()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    public function getLevelNameAttribute()
    {
        return $this->level == 0 ? 'Menu' : 'Sub Menu Level ' . $this->level;
    }

    // 🔥 slug full: nasional/politik
    public function getFullSlugAttribute()
    {
        return $this->parent
            ? $this->parent->slug . '/' . $this->slug
            : $this->slug;
    }

    /**
     * tambah kode baru
     **/
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Boot function untuk generate slug otomatis
     **/
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relasi ke articles - INI YANG PERLU DITAMBAHKAN
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }


    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get route key untuk slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
