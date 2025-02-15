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
        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->primary(['monitor_id', 'channel_id']);
        });

        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->dropIndex(['monitor_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->index(['monitor_id', 'channel_id']);
        });

        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->dropPrimary();
        });

        Schema::table('channel_monitor', function (Blueprint $table) {
            $table->id();
        });
    }
};
