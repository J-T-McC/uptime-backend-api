<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MonitorUptimeEventCount;

class MonitorUptimeEventCountFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = MonitorUptimeEventCount::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        $date = Carbon::now()->subDays($this->faker->numberBetween(1, 365));
        return [
            'monitor_id' => \App\Models\Monitor::factory(),
            'user_id' => \App\Models\User::factory(),
            'up' => $this->faker->numberBetween(1,30),
            'recovered' => $this->faker->numberBetween(1,30),
            'down' => $this->faker->numberBetween(1,30),
            'filter_date' => $date->format('Y-m-d'),
        ];
    }
}
