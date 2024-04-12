<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lift;
use App\Models\Set;
use App\Models\WorkoutSession;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lifts = Lift::factory(5)->create();
        WorkoutSession::factory(5)
                      ->has(Set::factory()
                               ->count(15)
                               ->sequence(fn(Sequence $sequence) => [
                                   'order'   => $sequence->index,
                                   'lift_id' => match (true) {
                                        $sequence->index % 15 < 3 => $lifts[0]->id,
                                        $sequence->index % 15 < 6 => $lifts[1]->id,
                                        $sequence->index % 15 < 9 => $lifts[2]->id,
                                        $sequence->index % 15 < 12 => $lifts[3]->id,
                                        $sequence->index % 15 < 15 => $lifts[4]->id,
                                   }
                               ]))
                      ->create();
    }
}
