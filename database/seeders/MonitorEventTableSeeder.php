<?php

namespace Database\Seeders;

use App\Models\MonitorEvent;
use Illuminate\Database\Seeder;

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
