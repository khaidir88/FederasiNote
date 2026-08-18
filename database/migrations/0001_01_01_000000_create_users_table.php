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
        // Users table
        Schema::create('users', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Authentication fields
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Personal information
            $table->string('nik', 16)
                ->nullable()
                ->unique()
                ->comment('Nomor Induk Kependudukan (16 digit)');
            $table->string('name', 100);
            $table->string('tempat_lahir', 50)
                ->nullable()
                ->comment('Tempat kelahiran');
            $table->date('tanggal_lahir')
                ->nullable()
                ->comment('Tanggal lahir (YYYY-MM-DD)');

            // Physical attributes
            $table->decimal('berat_badan', 5, 2)
                ->nullable()
                ->comment('Berat badan dalam kilogram');
            $table->decimal('tinggi_badan', 5, 2)
                ->nullable()
                ->comment('Tinggi badan dalam centimeter');

            // Contact information
            $table->string('no_hp', 20)
                ->nullable()
                ->index()
                ->comment('Nomor handphone (format: +6281234567890)');

            // Timestamps
            $table->timestamps();

            // Additional indexes
            $table->index('nik');
            $table->index('name');
        });

        // Password reset tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
