<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 音声ファイルを Base64 データURI 形式で保持する(画像と同じ方式)。
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->longText('audio')->nullable()->after('likes_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('audio');
        });
    }
};
