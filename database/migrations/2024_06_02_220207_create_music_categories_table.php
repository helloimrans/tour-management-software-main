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
        Schema::create('music_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('radio_station_id')->nullable(); // Nullable FK
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('descripiton')->nullable();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
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
        Schema::dropIfExists('music_categories');
    }
};
