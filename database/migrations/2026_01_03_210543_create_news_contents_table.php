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
        Schema::create('news_contents', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel news
            $table->foreignId('news_id')
                ->constrained('news')
                ->cascadeOnDelete();

            // Jenis konten
            $table->enum('type', ['text', 'image'])
                ->comment('text = paragraf, image = gambar + caption');

            // Paragraf / caption gambar
            $table->text('content')->nullable();

            // Path gambar (jika type = image)
            $table->string('image_path')->nullable();

            // Urutan tampil di halaman berita
            $table->unsignedInteger('order')->default(0);

            $table->timestamps();

            // Index untuk performa
            $table->index(['news_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_contents');
    }
};
