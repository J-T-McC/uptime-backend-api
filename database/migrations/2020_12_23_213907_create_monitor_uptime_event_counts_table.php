<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorUptimeEventCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_uptime_event_counts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('monitor_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedBigInteger('up')->default(0);
            $table->unsignedBigInteger('recovered')->default(0);
            $table->unsignedBigInteger('down')->default(0);

            $table->unsignedTinyInteger('filter_day');
            $table->unsignedTinyInteger('filter_week');
            $table->unsignedTinyInteger('filter_month');
            $table->unsignedSmallInteger('filter_year');

            $table->unique(['monitor_id', 'filter_day','filter_week','filter_month', 'filter_year'], 'distinct_date_counts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor_uptime_event_counts');
    }
}
