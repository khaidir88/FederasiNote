<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('url')->nullable();

            $table->enum('position', ['header', 'footer', 'sidebar'])
                ->default('header');

            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('icon')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
