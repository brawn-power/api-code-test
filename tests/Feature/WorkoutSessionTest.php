<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lift;
use App\Models\Set;
use App\Models\WorkoutSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class WorkoutSessionTest extends TestCase
{
    use RefreshDatabase;

    public function fakeDataWorkoutSessions(User $user, $lifts, int $total = 5)
    {
        return WorkoutSession::factory($total)
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
                ])
            )
            ->sequence(fn (Sequence $sequence) => [
                'start_at' => '2024-04-17 10:22:05',
                'end_at' => '2024-04-17 11:22:05',
            ])
            ->create([
                'user_id' => $user->id,
            ]);
    }

    public function test_api_get_workout_sessions_with_pagination_perpage_15()
    {
        $totalWorkoutSessions = 20;
        $user = User::factory()->create();
        $lifts = Lift::factory(5)->create();
        $workoutSessions = $this->fakeDataWorkoutSessions($user, $lifts, $totalWorkoutSessions);

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions')
                        ->assertOk();

        $this->assertEquals(15, $response['workout_sessions']['per_page']);
        $this->assertEquals($totalWorkoutSessions, $response['workout_sessions']['total']);
        $this->assertEquals($user->sets()->max('weight'), $response['max_weight']);
    }

    public function test_filter_workout_sessions_by_lift_id()
    {
        $user = User::factory()->create();
        $lifts = Lift::factory(5)->create();
        $workoutSessions = $this->fakeDataWorkoutSessions($user, $lifts);

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions?lift_id='.$lifts[0]->id)
                        ->assertOk();
    }

    public function test_filter_workout_sessions_by_range_dates()
    {
        $totalWorkoutSessions = 10;
        $user = User::factory()->create();
        $lifts = Lift::factory(5)->create();
        $workoutSessions = $this->fakeDataWorkoutSessions($user, $lifts, $totalWorkoutSessions);

        $workoutSessions->find($workoutSessions[0]->id)->update([
            "start_at" => "2024-04-10 10:22:05",
            "end_at" => "2024-04-10 11:22:05",
        ]);

        $workoutSessions->find($workoutSessions[1]->id)->update([
            "start_at" => "2024-04-11 10:22:05",
            "end_at" => "2024-04-11 11:22:05",
        ]);

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions?date_from=2024-04-09&date_to=2024-04-12')
                        ->assertOk();

        $count = WorkoutSession::query()
                    ->whereDate('start_at', '>=', '2024-04-09')
                    ->whereDate('end_at', '<=', '2024-04-12')
                    ->count();

        $this->assertEquals($count, $response['workout_sessions']['total']);
    }

    public function test_validated_field_lift_id_invalid_when_filter_workout_sessions(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions?lift_id=2')
                        ->assertStatus(422)
                        ->assertJsonValidationErrors(['lift_id' => 'The selected lift id is invalid.']);
    }

    public function test_validated_fields_date_invalid_when_filter_workout_sessions(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions?date_from=invalid&date_to=invalid')
                        ->assertStatus(422)
                        ->assertJsonValidationErrors([
                            'date_from' => 'The date from field must be a valid date.',
                            'date_to' => 'The date to field must be a valid date.',
                        ]);
    }

    public function test_validated_range_dates_invalid_when_filter_workout_sessions(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->getJson('/api/workout-sessions?date_from=2024-04-17&date_to=2024-04-10')
                        ->assertStatus(422)
                        ->assertJsonValidationErrors([
                            'date_from' => 'The date from field must be a date before date to.',
                            'date_to' => 'The date to field must be a date after date from.',
                        ]);
    }
}
