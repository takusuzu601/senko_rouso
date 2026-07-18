<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('お知らせの新規作成') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.announcements.store') }}">
                    @csrf
                    @include('admin.announcements._form')

                    <div class="mt-6 flex items-center gap-4">
                        <x-primary-button>{{ __('作成する') }}</x-primary-button>
                        <a href="{{ route('admin.announcements.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            キャンセル
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
