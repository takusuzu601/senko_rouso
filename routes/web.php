<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::post('/announcements/{announcement}/like', [AnnouncementController::class, 'like'])->name('announcements.like');
Route::get('/announcements/{announcement}/audio', [AnnouncementController::class, 'audio'])->name('announcements.audio');

Route::get('/topics', [TopicController::class, 'index'])->name('topics.index');
Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
Route::post('/topics/{topic}/like', [TopicController::class, 'like'])->name('topics.like');
Route::get('/topics/{topic}/audio', [TopicController::class, 'audio'])->name('topics.audio');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('admin/announcements', AdminAnnouncementController::class)
        ->except(['show'])
        ->names('admin.announcements')
        ->parameters(['announcements' => 'announcement']);

    Route::resource('admin/topics', AdminTopicController::class)
        ->except(['show'])
        ->names('admin.topics')
        ->parameters(['topics' => 'topic']);
});

require __DIR__.'/auth.php';
