<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dinas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'struktur',
        'link',
        'ket',
        'kategori'
    ];

    // Generate slug otomatis
    protected static function booted()
    {
        static::creating(function ($dinas) {
            $dinas->slug = Str::slug($dinas->nama);
        });

        static::updating(function ($dinas) {
            $dinas->slug = Str::slug($dinas->nama);
        });
    }

    // Akses link otomatis
    // public function getLinkAttribute()
    // {
    //     return url('/dinas/' . $this->slug);
    // }

    public function agendas()
    {
        return $this->hasMany(\App\Models\Agenda::class, 'dinas_id');
    }
}
