<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('トピック管理') }}
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
                    <h3 class="text-lg font-semibold text-gray-800">トピック一覧</h3>
                    <a href="{{ route('admin.topics.create') }}"
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
                        @forelse ($topics as $topic)
                            <tr>
                                <td class="px-6 py-4 text-gray-800">{{ $topic->title }}</td>
                                <td class="px-6 py-4">
                                    {{-- トグルを押した時点で公開/非公開を切り替える --}}
                                    <form method="POST" action="{{ route('admin.topics.toggle', $topic) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" role="switch"
                                                aria-checked="{{ $topic->is_published ? 'true' : 'false' }}"
                                                aria-label="{{ $topic->title }} の公開状態を切り替える"
                                                class="group inline-flex items-center gap-2">
                                            <span class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition
                                                         {{ $topic->is_published ? 'bg-[#8CC63F]' : 'bg-gray-300' }}">
                                                <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition
                                                             {{ $topic->is_published ? 'translate-x-[22px]' : 'translate-x-0.5' }}"></span>
                                            </span>
                                            <span class="text-xs {{ $topic->is_published ? 'text-green-700' : 'text-gray-500' }}">
                                                {{ $topic->is_published ? '公開中' : '非公開' }}
                                            </span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ optional($topic->published_at)->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="{{ route('admin.topics.edit', $topic) }}"
                                       class="text-indigo-600 hover:text-indigo-800">編集</a>
                                    <form method="POST" action="{{ route('admin.topics.destroy', $topic) }}"
                                          class="inline"
                                          onsubmit="return confirm('このトピックを削除しますか?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">削除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    トピックがまだありません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-6">
                    {{ $topics->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
