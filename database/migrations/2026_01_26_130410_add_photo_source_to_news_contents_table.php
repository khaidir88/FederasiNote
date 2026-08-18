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
            $table->string('photo_source', 255)
                ->nullable()
                ->after('image_path')
                ->comment('Sumber foto, contoh: Humas / Unsplash');
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
