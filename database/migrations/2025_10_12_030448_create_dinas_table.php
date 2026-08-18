<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dinas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('struktur')->nullable();
            $table->string('link')->nullable();
            $table->text('ket')->nullable();
            $table->enum('kategori', ['kota', 'provinsi'])->default('kota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dinas');
    }
};
