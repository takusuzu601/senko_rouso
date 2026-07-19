<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 本番(Render等のHTTPSプロキシ配下)では、生成URL(CSS/JSのアセット含む)を
        // 必ず https にする。これをしないと http でリンクが生成され、
        // ブラウザに mixed content としてブロックされ Tailwind CSS が効かなくなる。
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
