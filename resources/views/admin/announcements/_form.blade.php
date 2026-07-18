@php
    $maxLength = 2000;
@endphp

<div
    x-data="{
        title: {{ Illuminate\Support\Js::from(old('title', $announcement->title)) }},
        body: {{ Illuminate\Support\Js::from(old('body', $announcement->body)) }},
        isPublished: {{ Illuminate\Support\Js::from((bool) old('is_published', $announcement->is_published)) }},
        showPreview: false,
        maxLength: {{ $maxLength }},
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
            :value="old('published_at', optional($announcement->published_at)->format('Y-m-d'))" />
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
