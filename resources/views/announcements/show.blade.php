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
        </div>
    </article>
</x-layouts.public>
