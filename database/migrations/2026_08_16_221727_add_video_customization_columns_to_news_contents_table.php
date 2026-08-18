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
        $table->integer('video_height')->nullable()->default(350)->after('video_width');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_contents', function (Blueprint $table) {
            
            $table->dropColumn([
                'video_height'
            ]);
    
        });
    }
};
