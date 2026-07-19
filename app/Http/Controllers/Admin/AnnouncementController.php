<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::orderByDesc('id')->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $announcement = new Announcement();

        return view('admin.announcements.create', compact('announcement'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->encodeImage($request->file('image'));
        }

        Announcement::create($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'お知らせを作成しました。');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->encodeImage($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = null;
        }

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'お知らせを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'お知らせを削除しました。');
    }

    private function validateRequest(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
        ]);

        // image はファイルなので、DBへ保存する配列からは除外(別途エンコードして格納)
        unset($validated['image']);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }

    /**
     * アップロードされた画像を最大幅までリサイズ・JPEG圧縮し、
     * Base64 データURI 文字列にして返す(DB保存用)。
     */
    private function encodeImage(UploadedFile $file): string
    {
        $maxWidth = 1280;
        $raw = file_get_contents($file->getRealPath());

        // GD が使えない/デコードできない場合は元データをそのまま格納(フォールバック)
        $src = @imagecreatefromstring($raw);
        if ($src === false) {
            return 'data:'.$file->getMimeType().';base64,'.base64_encode($raw);
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $scale = min(1, $maxWidth / max(1, $w));
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        // 透過は白背景に平坦化(JPEG化のため)
        $dst = imagecreatetruecolor($nw, $nh);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagedestroy($src);

        ob_start();
        imagejpeg($dst, null, 80);
        $jpeg = ob_get_clean();
        imagedestroy($dst);

        return 'data:image/jpeg;base64,'.base64_encode($jpeg);
    }
}
