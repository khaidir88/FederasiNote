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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('module')->nullable()->after('guard_name');
            $table->text('description')->nullable()->after('module');

            // Tambahkan index untuk performa
            $table->index('module');
            $table->index(['module', 'guard_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['module']);
            $table->dropIndex(['module', 'guard_name']);

            $table->dropColumn(['module', 'description']);
        });
    }
};
