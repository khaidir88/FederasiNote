<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinas_id',
        'judul',
        'slug',
        'deskripsi',
        'foto',
        'link',
        'video',
        'author_id',
    ];


    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($agenda) {
            $agenda->slug = Str::slug($agenda->judul);
        });
    }
    // Relasi ke dinas
    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    // Relasi ke user (author)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
