@php
    $maxLength = 2000;
@endphp

<div
    x-data="{
        title: {{ Illuminate\Support\Js::from(old('title', $topic->title)) }},
        body: {{ Illuminate\Support\Js::from(old('body', $topic->body)) }},
        isPublished: {{ Illuminate\Support\Js::from((bool) old('is_published', $topic->is_published)) }},
        showPreview: false,
        maxLength: {{ $maxLength }},
        imagePreview: {{ Illuminate\Support\Js::from($topic->image) }},
        hasCurrentImage: {{ Illuminate\Support\Js::from((bool) $topic->image) }},
        removeImage: false,
        onImageChange(event) {
            const file = event.target.files[0];
            if (! file) return;
            const reader = new FileReader();
            reader.onload = e => { this.imagePreview = e.target.result; this.removeImage = false; };
            reader.readAsDataURL(file);
        },
        audioPreview: {{ Illuminate\Support\Js::from($topic->audio) }},
        hasCurrentAudio: {{ Illuminate\Support\Js::from((bool) $topic->audio) }},
        removeAudio: false,
        onAudioChange(event) {
            const file = event.target.files[0];
            if (! file) return;
            const reader = new FileReader();
            reader.onload = e => { this.audioPreview = e.target.result; this.removeAudio = false; };
            reader.readAsDataURL(file);
        },
    }"
    class="space-y-6"
>
    <div>
        <x-input-label for="title" value="タイトル" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
            x-model="title" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label value="メイン画像" />
        <p class="mt-1 text-xs text-gray-500">
            一覧・詳細ページの先頭に大きく表示されます。推奨は横長(16:9)。アップロード時に自動で圧縮・リサイズされます。
        </p>

        {{-- プレビュー(現在の画像 or 選択した画像) --}}
        <template x-if="imagePreview && ! removeImage">
            <div class="mt-3 aspect-[16/9] w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-100">
                <img :src="imagePreview" alt="プレビュー" class="w-full h-full object-cover">
            </div>
        </template>

        <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
            @change="onImageChange($event)"
            class="mt-3 block w-full text-sm text-gray-600
                   file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2
                   file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" />

        {{-- 編集時: 現在の画像を削除するオプション --}}
        <template x-if="hasCurrentImage">
            <label class="mt-2 inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remove_image" value="1" x-model="removeImage"
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                <span class="ms-2">現在の画像を削除する</span>
            </label>
        </template>

        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>

    <div>
        <x-input-label value="音声ファイル" />
        <p class="mt-1 text-xs text-gray-500">
            一覧・詳細ページに再生ボタンが表示され、モーダルで再生できます。
        </p>

        {{-- プレビュー(現在の音声 or 選択した音声) --}}
        <template x-if="audioPreview && ! removeAudio">
            <audio :src="audioPreview" controls class="mt-3 w-full"></audio>
        </template>

        <input type="file" name="audio" accept="audio/*"
            @change="onAudioChange($event)"
            class="mt-3 block w-full text-sm text-gray-600
                   file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2
                   file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" />

        {{-- 編集時: 現在の音声を削除するオプション --}}
        <template x-if="hasCurrentAudio">
            <label class="mt-2 inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" name="remove_audio" value="1" x-model="removeAudio"
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                <span class="ms-2">現在の音声ファイルを削除する</span>
            </label>
        </template>

        <x-input-error :messages="$errors->get('audio')" class="mt-2" />
    </div>

    <div>
        <div class="flex items-center justify-between">
            <x-input-label for="body" value="本文" />
            <button type="button" @click="showPreview = ! showPreview"
                class="text-sm text-indigo-600 hover:text-indigo-800">
                <span x-show="!showPreview">プレビューを表示</span>
                <span x-show="showPreview">編集に戻る</span>
            </button>
        </div>

        <textarea id="body" name="body" rows="10"
            x-model="body"
            x-show="!showPreview"
            maxlength="{{ $maxLength }}"
            required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        ></textarea>

        <div x-show="showPreview" x-cloak
             class="mt-1 block w-full min-h-[16rem] rounded-md border border-gray-300 bg-gray-50 p-3 whitespace-pre-wrap text-gray-800">
            <span x-text="body"></span>
        </div>

        <p class="mt-1 text-sm text-gray-500 text-right">
            <span x-text="body.length"></span> / <span x-text="maxLength"></span> 文字
        </p>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="published_at" value="公開日" />
        <x-text-input id="published_at" name="published_at" type="date" class="mt-1 block w-full"
            :value="old('published_at', optional($topic->published_at)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
    </div>

    <div class="flex items-center">
        <input type="hidden" name="is_published" value="0">
        <input id="is_published" type="checkbox" name="is_published" value="1"
            x-model="isPublished"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <label for="is_published" class="ms-2 text-sm text-gray-700">公開する</label>
        <span class="ms-3 text-xs px-2 py-0.5 rounded-full"
              :class="isPublished ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600'"
              x-text="isPublished ? '公開中' : '非公開'"></span>
    </div>
</div>
