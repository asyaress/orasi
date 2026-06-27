<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'konten',
        'cover_path',
        'tags',
        'status',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopePublicOrder(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (filled($this->cover_path) && Str::startsWith($this->cover_path, 'images/')) {
            return asset($this->cover_path);
        }

        return filled($this->cover_path)
            ? Storage::disk('public')->url($this->cover_path)
            : null;
    }

    public function getPublishedLabelAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;

        return $date?->copy()->locale('id')->translatedFormat('d F Y') ?? '-';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
