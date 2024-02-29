<?php

namespace Database\Factories;

use App\Models\Lift;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Set>
 */
class SetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workout_id' => Workout::factory(),
            'reps'       => rand(1, 12),
            'weight'     => rand(1, 500),
            'lift_id'    => Lift::factory(),
            'order'      => 1 // should be set on creation
        ];
    }
}
