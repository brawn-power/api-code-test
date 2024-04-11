<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkoutSession;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorkoutSessionTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        if (User::count() == 0) {
            $this->seed();
        }
    }

    public function test_return_422_for_invalidated_query_parameters()
    {
        $user = User::first();

        $response = $this->actingAs($user)->getJson('/api/workout-sessions?date_from=wrong_date_format');

        $response->assertUnprocessable();
    }

    public function test_return_401_authorization_required_if_user_not_logged_in()
    {
        $response = $this->getJson('/api/workout-sessions');

        $response->assertUnauthorized();
    }

    public function test_can_get_workout_sessions_stats_without_filter()
    {
        $user = User::first();

        $response = $this->actingAs($user)->getJson('/api/workout-sessions');

        $response->assertSuccessful();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('success')
                ->has(
                    'data',
                    fn ($json) =>
                    $json->hasAll(['metadata', 'stats', 'items'])
                        ->has(
                            'items.0',
                            fn ($json) =>
                            $json->hasAll(['id', 'start_at', 'end_at', 'volume'])
                        )
                )
        );
    }

    public function test_can_get_workout_sessions_stats_with_filter()
    {
        $user = User::first();
        $reps = rand(1, 12);
        $workoutSessionCount = WorkoutSession::query()
            ->where('user_id', $user->id)
            ->whereHas('sets', function ($query) use ($reps) {
                $query->where('lift_id', 5);
                $query->where('reps', $reps);
            })
            ->take(15)
            ->get()
            ->count();

        $response = $this->actingAs($user)->getJson("/api/workout-sessions?lift_id=5&reps=$reps");

        $response->assertSuccessful();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('success')
                ->has(
                    'data',
                    fn ($json) =>
                    $json->hasAll(['metadata', 'stats'])
                        ->has('items', $workoutSessionCount)
                        ->has(
                            'items.0',
                            fn ($json) =>
                            $json->hasAll(['id', 'start_at', 'end_at', 'volume'])
                        )
                )
        );
    }

    public function test_can_get_workout_sessions_stats_with_pagination()
    {
        $user = User::first();
        $workoutSessionCount = WorkoutSession::query()
            ->where('user_id', $user->id)
            ->take(10)
            ->skip(10)
            ->get()
            ->count();

        $response = $this->actingAs($user)->getJson('/api/workout-sessions?page=2&page_size=10');

        $response->assertSuccessful();
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json
                ->has('success')
                ->has(
                    'data',
                    fn ($json) =>
                    $json->hasAll(['metadata', 'stats'])
                        ->has('items', $workoutSessionCount)
                        ->has(
                            'items.0',
                            fn ($json) =>
                            $json->hasAll(['id', 'start_at', 'end_at', 'volume'])
                        )
                )
        );
    }
}
