<x-layouts.public :title="'トピック一覧'">
    <a href="{{ route('announcements.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; お知らせ一覧に戻る</a>

    <h1 class="mt-4 text-2xl font-bold text-gray-800 mb-6">トピック</h1>

    @if ($topics->isEmpty())
        <p class="text-gray-500">現在トピックはありません。</p>
    @else
        <div class="space-y-4">
            @foreach ($topics as $topic)
                <a href="{{ route('topics.show', $topic) }}"
                   class="group flex bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    @if ($topic->image)
                        {{-- SP: 小さめの円形サムネイル / PC: 従来どおり左側の帯 --}}
                        <div class="shrink-0 self-center sm:self-stretch w-14 h-14 sm:w-36 sm:h-auto ms-3 my-3 sm:m-0 rounded-full sm:rounded-none overflow-hidden bg-gray-100">
                            <img src="{{ $topic->image }}" alt="{{ $topic->title }}" loading="lazy"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="min-w-0 flex-1 p-4 sm:p-5">
                        <div class="text-sm text-gray-500 mb-1">
                            {{ optional($topic->published_at)->format('Y年n月j日') ?? $topic->created_at->format('Y年n月j日') }}
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $topic->title }}</h2>
                        <p class="mt-2 text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($topic->body), 100) }}</p>

                        {{-- いいね & シェア(一覧では表示のみ / 操作は詳細ページで) & 再生ボタン --}}
                        <div class="mt-3 flex items-center gap-3">
                            <span class="inline-flex items-center gap-1 text-sm text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                </svg>
                                <span class="tabular-nums">{{ $topic->likes_count }}</span>
                            </span>

                            @if ($topic->audio)
                                <button type="button"
                                    @click.prevent.stop="$store.audioPlayer.show({{ Illuminate\Support\Js::from($topic->audio) }}, {{ Illuminate\Support\Js::from($topic->title) }})"
                                    aria-label="音声を再生"
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#8CC63F] text-white hover:opacity-90 transition">
                                    <svg class="w-3.5 h-3.5 ms-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </button>
                            @endif

                            <span class="ms-auto flex items-center gap-1.5" aria-hidden="true">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-900 text-white">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </span>
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#1877F2] text-white">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </span>
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#06C755] text-white">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 5.66 2 10.153c0 4.027 3.558 7.4 8.363 8.04.326.07.77.215.882.494.101.253.066.65.032.906l-.143.856c-.043.253-.201.99.868.54 1.069-.451 5.766-3.396 7.866-5.814C21.612 13.65 22 11.95 22 10.153 22 5.66 17.514 2 12 2z"/></svg>
                                </span>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $topics->links() }}
        </div>
    @endif
</x-layouts.public>
