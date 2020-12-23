<?php

namespace Database\Factories;

use App\Models\Enums\UptimeStatus;
use App\Models\MonitorEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonitorEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonitorEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category' => 1,
            'status' => !$this->faker->numberBetween(0, 3) ? $this->faker->numberBetween(1, 3) : 1,
            'monitor_id' => \App\Models\Monitor::factory()->create()->id,
            'user_id' => \App\Models\User::factory()->create()->id,
            'error' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-90 days', 'now')
        ];
    }
}
