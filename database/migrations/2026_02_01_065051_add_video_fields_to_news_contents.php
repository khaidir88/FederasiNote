<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news_contents', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('image_path');
            $table->string('youtube_url')->nullable()->after('video_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_contents', function (Blueprint $table) {
            //
        });
    }
};
