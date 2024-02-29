<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutSession>
 */
class WorkoutSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_id' => \App\Models\Workout::factory(),
            'user_id'    => \App\Models\User::factory(),
            'start_at'   => now(),
            'end_at'     => now()->addHour(),
        ];
    }
}
