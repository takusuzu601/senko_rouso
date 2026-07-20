<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Topic;

class DashboardController extends Controller
{
    public function index()
    {
        $publicUrl = route('announcements.index');
        $announcementCount = Announcement::count();
        $publishedCount = Announcement::published()->count();
        $topicCount = Topic::count();
        $publishedTopicCount = Topic::published()->count();

        return view('dashboard', compact(
            'publicUrl',
            'announcementCount',
            'publishedCount',
            'topicCount',
            'publishedTopicCount'
        ));
    }
}
