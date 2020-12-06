<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_monitor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('monitor_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('driver_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();

            $table->index(['monitor_id', 'driver_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_monitor');
    }
}
