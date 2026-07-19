<x-layouts.public :title="$announcement->title">
    <a href="{{ route('announcements.index') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; お知らせ一覧に戻る</a>

    <article class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if ($announcement->image)
            <div class="aspect-[16/9] w-full overflow-hidden bg-gray-100">
                <img src="{{ $announcement->image }}" alt="{{ $announcement->title }}"
                     class="w-full h-full object-cover">
            </div>
        @endif
        <div class="p-6 sm:p-8">
        <div class="text-sm text-gray-500 mb-2">
            {{ optional($announcement->published_at)->format('Y年n月j日') ?? $announcement->created_at->format('Y年n月j日') }}
        </div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $announcement->title }}</h1>
        <div class="mt-6 text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $announcement->body }}</div>

        {{-- いいね & シェア --}}
        <div class="mt-8 pt-6 border-t border-gray-100"
             x-data="{
                likes: {{ (int) $announcement->likes_count }},
                liked: false,
                loading: false,
                key: 'liked_announcement_{{ $announcement->id }}',
                init() { this.liked = localStorage.getItem(this.key) === '1'; },
                async toggle() {
                    if (this.liked || this.loading) return;
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route('announcements.like', $announcement) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json',
                            },
                        });
                        if (res.ok) {
                            const data = await res.json();
                            this.likes = data.likes;
                            this.liked = true;
                            localStorage.setItem(this.key, '1');
                        }
                    } finally {
                        this.loading = false;
                    }
                }
             }">
            <div class="flex flex-wrap items-center gap-3">
                {{-- いいね(機能あり) --}}
                <button type="button" @click="toggle()" :disabled="liked || loading"
                    class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition disabled:cursor-default"
                    :class="liked ? 'bg-pink-50 border-pink-200 text-pink-600' : 'bg-white border-gray-300 text-gray-700 hover:border-pink-300 hover:text-pink-600'">
                    <svg class="w-5 h-5" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                    <span>いいね</span>
                    <span class="tabular-nums" x-text="likes"></span>
                </button>

                {{-- シェア(デザインのみ / SNS設定は後で) --}}
                <div class="flex items-center gap-2 sm:ml-auto">
                    <span class="text-xs text-gray-400 me-1">シェア</span>

                    {{-- TODO: 各ボタンに実際のシェアURLを設定する --}}
                    <button type="button" aria-label="Xでシェア" title="Xでシェア（設定予定）"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-900 text-white hover:opacity-90 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </button>

                    <button type="button" aria-label="Facebookでシェア" title="Facebookでシェア（設定予定）"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#1877F2] text-white hover:opacity-90 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>

                    <button type="button" aria-label="LINEでシェア" title="LINEでシェア（設定予定）"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#06C755] text-white hover:opacity-90 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2C6.486 2 2 5.66 2 10.153c0 4.027 3.558 7.4 8.363 8.04.326.07.77.215.882.494.101.253.066.65.032.906l-.143.856c-.043.253-.201.99.868.54 1.069-.451 5.766-3.396 7.866-5.814C21.612 13.65 22 11.95 22 10.153 22 5.66 17.514 2 12 2zM8.108 12.58H6.127a.53.53 0 0 1-.53-.528V8.09a.53.53 0 0 1 1.058 0v3.434h1.453a.528.528 0 0 1 0 1.056zm2.072-.528a.53.53 0 0 1-1.058 0V8.09a.53.53 0 0 1 1.058 0v3.962zm4.741 0a.528.528 0 0 1-.95.317l-2.03-2.762v2.445a.53.53 0 0 1-1.057 0V8.09a.528.528 0 0 1 .95-.318l2.03 2.763V8.09a.53.53 0 0 1 1.057 0v3.962zm3.183-2.51a.528.528 0 0 1 0 1.056h-1.453v.925h1.453a.528.528 0 0 1 0 1.057h-1.981a.53.53 0 0 1-.53-.528V8.09a.53.53 0 0 1 .53-.528h1.981a.528.528 0 0 1 0 1.057h-1.453v.924h1.453z"/>
                        </svg>
                    </button>

                    <button type="button" aria-label="リンクをコピー" title="リンクをコピー（設定予定）"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101m-.758-4.899a4 4 0 0 0 5.656 0l4-4a4 4 0 0 0-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">※ シェアボタンは現在デザインのみです（リンク先は後ほど設定します）。</p>
        </div>
        </div>
    </article>
</x-layouts.public>
