<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::orderByDesc('id')->paginate(15);

        return view('admin.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $topic = new Topic();

        return view('admin.topics.create', compact('topic'));
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

        if ($request->hasFile('audio')) {
            $validated['audio'] = $this->encodeAudio($request->file('audio'));
        }

        Topic::create($validated);

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'トピックを作成しました。');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->encodeImage($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            $validated['image'] = null;
        }

        if ($request->hasFile('audio')) {
            $validated['audio'] = $this->encodeAudio($request->file('audio'));
        } elseif ($request->boolean('remove_audio')) {
            $validated['audio'] = null;
        }

        $topic->update($validated);

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'トピックを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()
            ->route('admin.topics.index')
            ->with('status', 'トピックを削除しました。');
    }

    private function validateRequest(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'audio' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac,mp4', 'max:20480'],
        ]);

        // image/audio はファイルなので、DBへ保存する配列からは除外(別途エンコードして格納)
        unset($validated['image'], $validated['audio']);

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

    /**
     * アップロードされた音声ファイルを Base64 データURI 文字列にして返す(DB保存用)。
     */
    private function encodeAudio(UploadedFile $file): string
    {
        $raw = file_get_contents($file->getRealPath());

        return 'data:'.$file->getMimeType().';base64,'.base64_encode($raw);
    }
}
