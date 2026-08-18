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

            $table->unsignedTinyInteger('video_width')
                ->default(100)
                ->after('video_path');

            $table->enum('video_align', [
                'left',
                'center',
                'right'
            ])->default('center')
                ->after('video_width');

            $table->unsignedTinyInteger('video_radius')
                ->default(12)
                ->after('video_align');
            
            $table->integer('video_height')->nullable()->default(350);
        });
    }

    public function down(): void
    {
        Schema::table('news_contents', function (Blueprint $table) {

            $table->dropColumn([
                'video_width',
                'video_align',
                'video_radius'
            ]);
        });
    }
};
