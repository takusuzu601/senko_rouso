<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
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
}
