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

    protected $casts = [
        // Jika ingin menambahkan casting
    ];

    // Generate slug otomatis dengan handling duplicate
    protected static function booted()
    {
        static::creating(function ($dinas) {
            $dinas->slug = $dinas->generateUniqueSlug();
        });

        static::updating(function ($dinas) {
            if ($dinas->isDirty('nama')) {
                $dinas->slug = $dinas->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug()
    {
        $slug = Str::slug($this->nama);
        $originalSlug = $slug;
        $count = 1;

        // Cek jika slug sudah ada
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? null)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Accessor untuk link
     * Jika link kosong, generate link internal
     */
    public function getLinkAttribute($value)
    {
        // Jika ada link external, gunakan itu
        if (!empty($value)) {
            return $value;
        }

        // Jika tidak ada link, generate link internal ke halaman dinas
        return url('/dinas/' . $this->slug);
    }

    /**
     * Scope untuk kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeKota($query)
    {
        return $query->where('kategori', 'kota');
    }

    public function scopeProvinsi($query)
    {
        return $query->where('kategori', 'provinsi');
    }

    /**
     * Relationship dengan Agenda
     */
    public function agendas()
    {
        return $this->hasMany(\App\Models\Agenda::class, 'dinas_id');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
