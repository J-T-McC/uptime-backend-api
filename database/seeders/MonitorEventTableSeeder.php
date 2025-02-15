<?php

namespace Database\Seeders;

use App\Models\Monitor;
use App\Models\MonitorEvent;
use Illuminate\Database\Seeder;

class MonitorEventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Monitor::query()->whereDoesntHave('monitorEvents')->cursor() as $monitor) {
            MonitorEvent::factory()->for($monitor)->count(40)->create([
                'user_id' => $monitor->user_id,
            ]);
        }
    }
}
