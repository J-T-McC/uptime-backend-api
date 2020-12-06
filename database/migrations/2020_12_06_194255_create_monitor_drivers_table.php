<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitor_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type', 25);
            $table->string('endpoint');
            $table->text('secret')->nullable();
            $table->timestamps();
            $table->unique(['monitor_id', 'type', 'endpoint']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitor_drivers');
    }
}
