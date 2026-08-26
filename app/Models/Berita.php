<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Berita extends Model
{
    use LogsActivity;

    protected $table = 'berita';

    protected static function booted(): void
    {
        static::saving(function (Berita $berita) {
            // Jaring pengaman bila slug tidak sempat ter-generate dari blur judul.
            if (blank($berita->slug) && filled($berita->judul)) {
                $berita->slug = self::buatSlugUnik($berita->judul, $berita->id);
            }
        });
    }

    /** Bangun slug unik dari judul; tambah suffix -2, -3, … bila sudah dipakai. */
    public static function buatSlugUnik(string $judul, ?int $ignoreId = null): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $counter = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    protected $fillable = [
        'judul', 'slug', 'kategori', 'tanggal', 'penulis', 'gambar',
        'ringkasan', 'isi', 'status', 'is_featured', 'views',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_featured' => 'boolean',
            'views' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'kategori', 'status', 'is_featured'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeTerbaru(Builder $query): Builder
    {
        return $query->orderByDesc('tanggal')->orderByDesc('id');
    }

    /** Cuplikan teks polos untuk kartu daftar berita. */
    public function getRingkasanFallbackAttribute(): string
    {
        if (filled($this->ringkasan)) {
            return $this->ringkasan;
        }

        return Str::limit(strip_tags($this->isi ?? ''), 160);
    }
}

