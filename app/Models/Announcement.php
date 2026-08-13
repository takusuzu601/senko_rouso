<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /** @use HasFactory<\Database\Factories\AnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image',
        'audio',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'date',
            'likes_count' => 'integer',
            'has_audio' => 'boolean',
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * 新しい投稿が上に来る並び順。
     * published_at が未入力のものは created_at で代用する
     * (Postgres の `ORDER BY ... DESC` は NULL を先頭に並べるため、
     *  公開日が空の投稿が最上位に居座ってしまうのを防ぐ)。
     */
    public function scopeLatestPublished($query)
    {
        return $query->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->orderByDesc('id');
    }
}
