<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsContent extends Model
{
    use HasFactory;

    protected $table = 'news_contents';

    protected $fillable = [
        'type',
        'content',
        'image_path',
        'video_path',
        'video_orientation',
        'youtube_url',
        'caption',
        'related_title',
        'related_url',
        'position',
        'news_id',

        'video_width',
        'video_height',
        'video_align',
        'video_radius',


    ];



    /**
     * Relasi ke berita utama
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }


    /**
     * Scope konten berurutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Helper: cek apakah konten gambar
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Helper: cek apakah konten teks
     */
    public function isText(): bool
    {
        return $this->type === 'text';
    }
}
