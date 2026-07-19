<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'お知らせ') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col">
        <header class="bg-[#8CC63F] shadow-sm" x-data="{ open: false }" @keydown.escape.window="open = false">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-3">
                {{-- ロゴ + サイト名 --}}
                <a href="{{ route('announcements.index') }}" class="flex items-center gap-2 sm:gap-3 min-w-0">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"
                         class="h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-white/10 object-contain shrink-0">
                    <span class="text-lg sm:text-2xl font-bold text-white truncate tracking-wide">
                        {{ config('app.name', 'お知らせ') }}
                    </span>
                </a>

                {{-- PC: SNSアイコン + ナビ(横並び) --}}
                <div class="hidden sm:flex items-center gap-4 shrink-0">
                    <a href="https://x.com/" target="_blank" rel="noopener noreferrer"
                       aria-label="X" title="X" class="text-white/90 hover:text-white transition">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer"
                       aria-label="YouTube" title="YouTube" class="text-white/90 hover:text-white transition">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12z"/>
                        </svg>
                    </a>
                    <a href="https://note.com/" target="_blank" rel="noopener noreferrer"
                       aria-label="note" title="note"
                       class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-white text-[#8CC63F] hover:bg-white/90 transition">
                        <span class="text-sm font-bold leading-none">n</span>
                    </a>
                    <span class="h-5 w-px bg-white/40"></span>
                    <nav class="text-sm">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-white/90 hover:text-white">管理画面へ</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white/90 hover:text-white">ログイン</a>
                        @endauth
                    </nav>
                </div>

                {{-- SP: ハンバーガーボタン --}}
                <button type="button" @click="open = ! open" :aria-expanded="open" aria-label="メニュー"
                        class="sm:hidden inline-flex items-center justify-center h-10 w-10 rounded-md text-white hover:bg-white/10 transition shrink-0">
                    <svg x-show="! open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- SP: 展開メニュー(SNS + ログイン) --}}
            <div x-show="open" x-cloak x-transition.origin.top
                 @click.outside="open = false"
                 class="sm:hidden border-t border-white/20">
                <nav class="max-w-4xl mx-auto px-4 py-2 flex flex-col">
                    <a href="https://x.com/" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 py-3 text-white hover:bg-white/10 rounded-md px-2 transition">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        <span class="text-sm font-medium">X</span>
                    </a>
                    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 py-3 text-white hover:bg-white/10 rounded-md px-2 transition">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12z"/>
                        </svg>
                        <span class="text-sm font-medium">YouTube</span>
                    </a>
                    <a href="https://note.com/" target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 py-3 text-white hover:bg-white/10 rounded-md px-2 transition">
                        <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-white text-[#8CC63F]">
                            <span class="text-xs font-bold leading-none">n</span>
                        </span>
                        <span class="text-sm font-medium">note</span>
                    </a>
                    <span class="my-1 h-px bg-white/20"></span>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="py-3 px-2 text-sm font-medium text-white hover:bg-white/10 rounded-md transition">管理画面へ</a>
                    @else
                        <a href="{{ route('login') }}"
                           class="py-3 px-2 text-sm font-medium text-white hover:bg-white/10 rounded-md transition">ログイン</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                {{ $slot }}
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'お知らせ') }}
            </div>
        </footer>
    </body>
</html>
