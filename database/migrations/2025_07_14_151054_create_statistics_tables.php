<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel statistik umum
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->decimal('metric_value', 15, 2);
            $table->date('date_recorded');
            $table->json('additional_data')->nullable();
            $table->morphs('statisticable');
            $table->timestamps();

            $table->index('metric_name');
            $table->index('date_recorded');
        });

        // Tabel statistik screening khusus
        Schema::create('screening_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_screenings')->default(0);
            $table->integer('low_risk_count')->default(0);
            $table->integer('medium_risk_count')->default(0);
            $table->integer('high_risk_count')->default(0);
            $table->date('date_recorded');
            $table->timestamps();

            $table->index('date_recorded');
        });

        // Tabel statistik pengguna
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_users')->default(0);
            $table->integer('active_users')->default(0);
            $table->integer('new_users')->default(0);
            $table->date('date_recorded');
            $table->timestamps();

            $table->index('date_recorded');
        });
    }

    public function down()
    {
        Schema::dropIfExists('statistics');
        Schema::dropIfExists('screening_statistics');
        Schema::dropIfExists('user_statistics');
    }
};
