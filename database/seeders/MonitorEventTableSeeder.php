<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Monitor;
use App\Models\MonitorEvent;

class MonitorEventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MonitorEvent::factory()->count(5000)->create();
    }
}
