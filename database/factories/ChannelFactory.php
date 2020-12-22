<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Channel;

class ChannelFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Channel::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition()
    {
        return [
            'endpoint' => $this->faker->unique()->word,
            'type' => $this->faker->unique()->word,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
