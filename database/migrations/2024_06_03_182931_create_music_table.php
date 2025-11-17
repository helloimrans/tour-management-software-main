<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_station_id')->nullable(); // Nullable FK
            $table->unsignedBigInteger('music_category_id')->nullable(); // Nullable FK
            $table->string('title')->unique();
            $table->longText('description')->nullable(); // Corrected column name
            $table->string('music_file')->nullable();
            $table->string('music_author')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->bigInteger('music_views')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->boolean('discussion_comments')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('radio_station_id')->references('id')->on('radio_stations');
            $table->foreign('music_category_id')->references('id')->on('music_categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};
