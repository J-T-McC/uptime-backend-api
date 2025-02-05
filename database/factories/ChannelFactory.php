<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     */
    public function definition(): array
    {
        $channels = $this->channels();

        $choice = array_rand($channels);

        return [
            'endpoint' => $channels[$choice],
            'type' => $choice,
            'description' => $this->faker->words(4, true),
            'user_id' => User::factory(),
        ];
    }

    public function channels(): array
    {
        return [
            'mail' => $this->faker->safeEmail,
            'slack' => $this->faker->url,
            'discord' => $this->faker->url,
        ];
    }
}
