<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * メイン画像を Base64 データURI 形式で保持する。
     * longText: pgsql では text(無制限)、mysql では LONGTEXT にマップされ、
     * 圧縮後の画像でも収まる。
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->longText('image')->nullable()->after('body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
