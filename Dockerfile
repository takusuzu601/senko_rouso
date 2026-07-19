# Laravel (PHP) を Render の Docker 環境で動かすための Dockerfile
FROM php:8.4-cli

# 必要なシステムパッケージと PHP 拡張
#  - pdo_pgsql / pgsql : Supabase(PostgreSQL)接続用
#  - mbstring          : Laravel 必須(ベースイメージに含まれないため明示インストール)
#  - gd                : simple-qrcode(QRコード生成)用
#  - zip / bcmath / intl / exif : Laravel でよく使われる拡張
RUN apt-get update && apt-get install -y \
        git curl zip unzip \
        libpq-dev libzip-dev libonig-dev libicu-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo pdo_pgsql pgsql mbstring gd zip bcmath intl exif \
    && rm -rf /var/lib/apt/lists/*

# Node.js (Vite でのフロントエンドビルド用)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 依存関係を先にインストール(キャッシュ効率のため)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY package.json package-lock.json ./
RUN npm ci

# アプリ本体をコピー
COPY . .

# フロントエンドをビルド & オートローダ最適化
RUN composer dump-autoload --optimize \
    && npm run build

# ストレージ等の権限
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# 起動時: 設定キャッシュ → マイグレーション → サーバー起動
# 環境変数(APP_KEY / DB_* など)は Render の Environment で注入される
CMD php artisan config:clear \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
