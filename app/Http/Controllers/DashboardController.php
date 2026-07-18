<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $publicUrl = route('announcements.index');
        $announcementCount = Announcement::count();
        $publishedCount = Announcement::published()->count();

        return view('dashboard', compact('publicUrl', 'announcementCount', 'publishedCount'));
    }
}
