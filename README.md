# senko_rouso お知らせCMS

Laravel + Blade + Alpine.js + Tailwind CSSで構築したシンプルな告知用CMSです。

## 主な機能

- 公開ページ:トップページでお知らせ一覧(ページネーション付き)、詳細ページで本文を表示
- 管理画面(要ログイン):`/admin/announcements` でお知らせのCRUD(作成・編集・削除・公開切り替え)
- サイト全体へのBasic認証(公開ページ・ログイン画面・管理画面すべてが対象)
- 管理画面ダッシュボードに公開トップページのQRコードを表示

## 技術スタック

- Laravel 12
- フロントエンド:Blade + Alpine.js + Tailwind CSS(Vite経由でビルド)
- 認証:Laravel Breeze(Bladeスタック)
- DB:ローカルはSQLite、本番は環境変数の切り替えのみでMySQL/PostgreSQLに対応
- QRコード生成:`simplesoftwareio/simple-qrcode`

## アクセス構成

1. サイト全体にBasic認証(`BASIC_AUTH_USER` / `BASIC_AUTH_PASS`)がかかります。
2. Basic認証を通過した上で、`/admin/announcements` などの管理画面にアクセスするにはログイン(`/login`)が必要です。

## ローカル環境構築手順

### 前提

- PHP 8.2以上
- Composer
- Node.js / npm

### セットアップ

```bash
# 1. 依存パッケージのインストール
composer install
npm install

# 2. 環境変数ファイルの準備
cp .env.example .env
php artisan key:generate

# 3. SQLiteデータベースファイルの作成
touch database/database.sqlite

# 4. マイグレーション + シーディング(ダミーのお知らせ5件、テストユーザーを作成)
php artisan migrate --seed

# 5. フロントエンドのビルド
npm run build
# 開発中はホットリロードしたい場合は別ターミナルで以下を実行
# npm run dev

# 6. サーバー起動
php artisan serve
```

`http://127.0.0.1:8000` にアクセスすると、Basic認証のダイアログが表示されます。
`.env` の `BASIC_AUTH_USER` / `BASIC_AUTH_PASS` に設定した値を入力してください(デフォルトは `admin` / `password`)。

管理画面へのログインには、シーダーで作成されるテストユーザーを利用できます。

- メールアドレス:`test@example.com`
- パスワード:`password`

ログイン後、右上のナビゲーションから「お知らせ管理」を選択するとCRUD画面に、「Dashboard」を選択するとQRコード付きのダッシュボードに移動できます。

## 本番用DBへの切り替え(Turso / Supabase)

Render無料プランはファイルシステムが一時的なため、本番ではSQLiteではなく外部DBを使用してください。
`config/database.php` は標準的なEloquent構成のままなので、`.env` の接続情報を書き換えるだけで切り替えられます。

### Supabase(PostgreSQL)を使う場合

```
DB_CONNECTION=pgsql
DB_HOST=<SupabaseのHost>
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=<SupabaseのUser>
DB_PASSWORD=<Supabaseのパスワード>
```

### Turso / その他MySQL互換DBを使う場合

TursoをMySQL/PostgreSQLワイヤープロトコル経由で利用できる場合は、同様に `DB_CONNECTION=mysql`(または`pgsql`)にして接続情報を設定してください。
Turso独自のlibSQLプロトコルを直接使う場合は別途対応ドライバの導入が必要なため、標準Eloquent構成のみで完結させたい場合はSupabaseの利用を推奨します。

## Renderへのデプロイ手順

1. GitHubなどにリポジトリをpushしておきます。
2. Renderで「New Web Service」を作成し、対象リポジトリを選択します。
3. 以下の設定を行います。

   - **Environment**: Docker ではなく `Native Environment` を選ぶ場合は Runtime に `PHP` を指定
   - **Build Command**:
     ```
     composer install --no-dev --optimize-autoloader && npm install && npm run build && php artisan migrate --force
     ```
   - **Start Command**:
     ```
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```

4. 環境変数(Environment Variables)に以下を設定します。

   | 変数名 | 説明 |
   | --- | --- |
   | `APP_NAME` | サイト名 |
   | `APP_ENV` | `production` |
   | `APP_KEY` | `php artisan key:generate --show` で生成した値 |
   | `APP_DEBUG` | `false` |
   | `APP_URL` | RenderのデプロイURL |
   | `APP_LOCALE` | `ja` |
   | `DB_CONNECTION` | `pgsql`(Supabase利用時)または `mysql` |
   | `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | 外部DBの接続情報(Turso/Supabaseから取得) |
   | `BASIC_AUTH_USER` | サイト全体のBasic認証ユーザー名 |
   | `BASIC_AUTH_PASS` | サイト全体のBasic認証パスワード |
   | `SESSION_DRIVER` | `database`(既定のまま) |
   | `QUEUE_CONNECTION` | `database`(既定のまま) |

5. 初回デプロイ後、必要であれば管理者ユーザーを作成してください(`php artisan tinker` などから `User::create([...])`、または任意のSeederを用意)。

## ディレクトリ構成の要点

- `app/Models/Announcement.php` — お知らせモデル
- `app/Http/Controllers/AnnouncementController.php` — 公開ページ用コントローラ
- `app/Http/Controllers/Admin/AnnouncementController.php` — 管理画面CRUD用コントローラ
- `app/Http/Controllers/DashboardController.php` — QRコード付きダッシュボード
- `app/Http/Middleware/BasicAuthMiddleware.php` — サイト全体のBasic認証
- `resources/views/announcements/` — 公開ページのビュー
- `resources/views/admin/announcements/` — 管理画面のビュー(Alpine.jsで文字数カウンター・プレビュー切り替えを実装)
- `database/seeders/AnnouncementSeeder.php` — ダミーお知らせ5件のシーダー
