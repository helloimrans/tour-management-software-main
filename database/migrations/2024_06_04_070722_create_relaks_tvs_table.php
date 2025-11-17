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
        Schema::create('relaks_tvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_station_id')->nullable();
            $table->string('youtube_url_1')->nullable();
            $table->string('youtube_url_2')->nullable();
            $table->string('youtube_url_3')->nullable();
            $table->foreign('radio_station_id')->references('id')->on('radio_stations');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relaks_tvs');
    }
};
