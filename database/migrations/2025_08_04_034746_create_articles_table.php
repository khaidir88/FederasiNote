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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('tags')->nullable();
            $table->string('slug')->unique(); // Slug untuk URL SEO-friendly
            $table->text('content');
            $table->string('image'); // Path gambar utama
            $table->string('image_caption')->nullable();
            $table->string('video_url')->nullable();
            $table->string('author'); // Nama penulis (ditambahkan)
            $table->foreignId('category_id')->nullable();
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi
            $table->unsignedBigInteger('views')->default(0); // Jumlah view
            $table->timestamps(); // created_at dan updated_at
            $table->softDeletes(); // deleted_at untuk soft delete
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_description')->nullable();

            // Index untuk pencarian
            $table->index('title');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
