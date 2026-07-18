<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お知らせ管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">お知らせ一覧</h3>
                    <a href="{{ route('admin.announcements.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        新規作成
                    </a>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">タイトル</th>
                            <th class="px-6 py-3">状態</th>
                            <th class="px-6 py-3">公開日</th>
                            <th class="px-6 py-3 text-right">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($announcements as $announcement)
                            <tr>
                                <td class="px-6 py-4 text-gray-800">{{ $announcement->title }}</td>
                                <td class="px-6 py-4">
                                    @if ($announcement->is_published)
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">公開中</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-600">非公開</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ optional($announcement->published_at)->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                       class="text-indigo-600 hover:text-indigo-800">編集</a>
                                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                                          class="inline"
                                          onsubmit="return confirm('このお知らせを削除しますか?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    お知らせがまだありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-6">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
