<?php

namespace App\Http\Controllers;

use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::published()
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
}
