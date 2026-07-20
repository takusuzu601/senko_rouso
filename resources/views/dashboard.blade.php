<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">お知らせ件数</div>
                    <div class="mt-1 text-3xl font-bold text-gray-800">{{ $announcementCount }}</div>
                    <div class="mt-1 text-xs text-gray-500">うち公開中 {{ $publishedCount }} 件</div>
                    <a href="{{ route('admin.announcements.index') }}"
                       class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                        お知らせを管理する &rarr;
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500">トピック件数</div>
                    <div class="mt-1 text-3xl font-bold text-gray-800">{{ $topicCount }}</div>
                    <div class="mt-1 text-xs text-gray-500">うち公開中 {{ $publishedTopicCount }} 件</div>
                    <a href="{{ route('admin.topics.index') }}"
                       class="mt-4 inline-block text-sm text-indigo-600 hover:text-indigo-800">
                        トピックを管理する &rarr;
                    </a>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 mb-3">公開サイトのQRコード</div>
                    <div class="flex items-center gap-4">
                        <div class="border border-gray-200 rounded-md p-2 bg-white">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->generate($publicUrl) !!}
                        </div>
                        <div class="text-sm text-gray-600 break-all">
                            {{ $publicUrl }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
