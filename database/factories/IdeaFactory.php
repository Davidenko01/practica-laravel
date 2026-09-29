<?php

namespace Database\Factories;

use App\IdeaState;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(200),
            'links' => [fake()->url()],
            'state' => fake()->randomElement(IdeaState::cases()),
        ];
    }
}
