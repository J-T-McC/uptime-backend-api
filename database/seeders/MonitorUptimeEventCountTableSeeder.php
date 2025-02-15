<?php

namespace Database\Seeders;

use App\Models\Monitor;
use App\Models\MonitorUptimeEventCount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MonitorUptimeEventCountTableSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Monitor::query()->whereDoesntHave('uptimeEventCounts')->cursor() as $monitor) {
            foreach (Carbon::now()->subDays(30)->daysUntil(Carbon::now()) as $date) {
                $percentUptime = rand(90, 100);
                $maxUp = 1440;
                $upCount = $maxUp * ($percentUptime / 100);
                $downCount = $maxUp - $upCount;
                $recovered = rand(0, $downCount);

                MonitorUptimeEventCount::factory()->for($monitor)->create([
                    'user_id' => $monitor->user_id,
                    'filter_date' => $date->format('Y-m-d'),
                    'up' => $upCount,
                    'down' => $downCount,
                    'recovered' => $recovered,
                ]);
            }
        }
    }
}
