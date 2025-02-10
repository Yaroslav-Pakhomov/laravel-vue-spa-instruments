<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        $users_ids = User::query()->get()->pluck('id');
        $messages_ids = Message::query()->get()->pluck('id');

        return [
            'body' => fake()->realText(random_int(100, 120)),

            'user_id' => $users_ids->count() > 0 ? $this->faker->unique()->numberBetween(
                1,
                $users_ids->count()
            ) : null,
            // 'parent_id' => $messages_ids->count() > 0 ? $this->faker->numberBetween(1, $messages_ids->count()) : null,
            'parent_id' => Message::all()->count() > 0 ? Message::all()->random()->id : null,

            'created_at' => fake()->dateTimeBetween('-60 days', '-30 days'),
            'updated_at' => fake()->dateTimeBetween('-20 days', '-1 days'),
        ];
    }
}
