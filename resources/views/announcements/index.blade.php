<x-layouts.public :title="'お知らせ一覧'">
    {{-- このサイトについて --}}
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="inline-block w-1.5 h-6 bg-[#8CC63F] rounded"></span>
            このサイトについて
        </h2>
        <p class="text-gray-600 leading-relaxed text-sm">
            本サイトは、職場で発生した労働問題やハラスメント、不適切な労務管理について、客観的な証拠と事実に基づき記録・公開するための情報サイトです。労働組合（ユニオン）への加入後、団体交渉や各関係機関への相談を進める中で、交渉経過や改善状況を社会へ広く発信し、透明性を確保することを目的としています。掲載内容は録音、文書、勤務記録などの客観的資料に基づき、必要に応じて更新します。本サイトを通じて、適正な労務管理の実現と再発防止を促し、同様の問題で悩む労働者への情報提供にも役立てていきます。
        </p>
    </section>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">お知らせ</h1>

    @if ($announcements->isEmpty())
        <p class="text-gray-500">現在お知らせはありません。</p>
    @else
        <div class="space-y-4">
            @foreach ($announcements as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}"
                   class="group block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    @if ($announcement->image)
                        <div class="aspect-[16/9] w-full overflow-hidden bg-gray-100">
                            <img src="{{ $announcement->image }}" alt="{{ $announcement->title }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="text-sm text-gray-500 mb-1">
                            {{ optional($announcement->published_at)->format('Y年n月j日') ?? $announcement->created_at->format('Y年n月j日') }}
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $announcement->title }}</h2>
                        <p class="mt-2 text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($announcement->body), 100) }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $announcements->links() }}
        </div>
    @endif
</x-layouts.public>
