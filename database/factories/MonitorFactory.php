<?php

namespace Database\Factories;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Monitor::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->unique->url,
            'uptime_check_method' => 'get',
        ];
    }
}
