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
        Schema::create('total_viewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_station_id')->nullable();
            $table->bigInteger('viewers')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('radio_station_id')->references('id')->on('radio_stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_viewers');
    }
};
