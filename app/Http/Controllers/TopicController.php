<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class TopicController extends Controller
{
    private const LIST_COLUMNS = [
        'id', 'title', 'body', 'image', 'likes_count',
        'is_published', 'published_at', 'created_at', 'updated_at',
    ];

    public function index()
    {
        $topics = Topic::published()
            ->select(self::LIST_COLUMNS)
            ->selectRaw('audio IS NOT NULL as has_audio')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
        if (! $topic->is_published) {
            abort(404);
        }

        return view('topics.show', compact('topic'));
    }

    /**
     * いいねを +1 する。二重押し防止はフロント(localStorage)側で行う。
     */
    public function like(Topic $topic)
    {
        if (! $topic->is_published) {
            abort(404);
        }

        $topic->increment('likes_count');

        return response()->json(['likes' => $topic->likes_count]);
    }

    /**
     * 音声を再生時にのみ配信する(一覧・詳細ページのHTMLには base64 を埋め込まない)。
     */
    public function audio(Topic $topic)
    {
        if (! $topic->is_published || ! $topic->audio) {
            abort(404);
        }

        return $this->streamAudioDataUri($topic->audio);
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
