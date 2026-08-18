<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'name',
        'email',
        'content',
        'approved'
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    /**
     * Relasi ke artikel
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * Scope untuk komentar yang disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope untuk komentar yang belum disetujui
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }
}
