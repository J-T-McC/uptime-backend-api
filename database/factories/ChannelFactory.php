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

        $channels = [
            'mail' => $this->faker->safeEmail,
            'discord' => $this->faker->url,
            'slack' => $this->faker->url,
        ];

        $choice = array_rand($channels);

        return [
            'endpoint' => $channels[$choice],
            'type' => $choice,
            'user_id' => \App\Models\User::factory()->create()->id,
        ];
    }
}
