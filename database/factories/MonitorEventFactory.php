<?php

namespace Database\Factories;

use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\User;
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
     */
    public function definition(): array
    {
        return [
            'category' => 1,
            'status' => ! $this->faker->numberBetween(0, 3) ? $this->faker->numberBetween(1, 3) : 1,
            'monitor_id' => Monitor::factory(),
            'user_id' => User::factory(),
            'error' => $this->faker->realText(100),
            'created_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
