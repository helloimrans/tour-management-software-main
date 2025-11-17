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
        Schema::table('radio_stations', function (Blueprint $table) {
            $table->string('live_youtube_url')->nullable()->after('live_radio_url');
            $table->boolean('is_relaks_tv')->default(0)->after('whatsapp_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('radio_stations', function (Blueprint $table) {
            $table->dropColumn(['live_youtube_url', 'is_relaks_tv']);
        });
    }
};
