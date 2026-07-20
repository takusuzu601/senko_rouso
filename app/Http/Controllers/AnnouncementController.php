<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Topic;

class AnnouncementController extends Controller
{
    /**
     * 一覧表示に不要な audio 列(base64の巨大なテキスト)を除外して取得する共通カラム指定。
     * 再生ボタンの表示可否は has_audio フラグで判定し、実データは再生時に audio() から個別取得する。
     */
    private const LIST_COLUMNS = [
        'id', 'title', 'body', 'image', 'likes_count',
        'is_published', 'published_at', 'created_at', 'updated_at',
    ];

    public function index()
    {
        $announcements = Announcement::published()
            ->select(self::LIST_COLUMNS)
            ->selectRaw('audio IS NOT NULL as has_audio')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        $topics = Topic::published()
            ->select(self::LIST_COLUMNS)
            ->selectRaw('audio IS NOT NULL as has_audio')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('announcements.index', compact('announcements', 'topics'));
    }

    public function show(Announcement $announcement)
    {
        if (! $announcement->is_published) {
            abort(404);
        }

        return view('announcements.show', compact('announcement'));
    }

    /**
     * いいねを +1 する。二重押し防止はフロント(localStorage)側で行う。
     */
    public function like(Announcement $announcement)
    {
        if (! $announcement->is_published) {
            abort(404);
        }

        $announcement->increment('likes_count');

        return response()->json(['likes' => $announcement->likes_count]);
    }

    /**
     * 音声を再生時にのみ配信する(一覧・詳細ページのHTMLには base64 を埋め込まない)。
     */
    public function audio(Announcement $announcement)
    {
        if (! $announcement->is_published || ! $announcement->audio) {
            abort(404);
        }

        return $this->streamAudioDataUri($announcement->audio);
    }

    private function streamAudioDataUri(string $dataUri)
    {
        [$meta, $base64] = explode(',', $dataUri, 2);
        preg_match('/^data:(.*?);base64$/', $meta, $matches);

        return response(base64_decode($base64), 200, [
            'Content-Type' => $matches[1] ?? 'application/octet-stream',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
