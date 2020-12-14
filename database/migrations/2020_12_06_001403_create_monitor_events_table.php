<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('monitor_id')
                ->constrained()
                ->onDelete('cascade');

            $table->unsignedTinyInteger('category');
            $table->unsignedTinyInteger('status');

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
        Schema::dropIfExists('monitor_events');
    }
}
