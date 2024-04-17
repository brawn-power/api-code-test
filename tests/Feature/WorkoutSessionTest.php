<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class WorkoutSessionTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function test_get_workout_sessions_data_pagination()
    {
        $this->seed();
        $response = $this->getJson(route('workout-sessions.index'));
        $response->assertStatus(200)
            // test data structure
            ->assertJsonStructure(
                [
                    'data'  => [
                        '*' => [
                            'id',
                            'user_id',
                            'start_at',
                            'end_at',
                            'sets' => [
                                '*' => [
                                    'reps',
                                    'weight',
                                    'volume',
                                    'lift' => [
                                        'id',
                                        'name'
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'stats' => [
                        'max_weight',
                        'max_volume'
                    ],
                    'meta'  => [
                        'current_page',
                        'from',
                        'to',
                        'last_page',
                        'per_page',
                        'total'
                    ]
                ]
            )
            // test paginate 15 entries per page
            ->assertJsonPath('meta.per_page', 15);

        // test order by latest first
        $data = json_decode($response->getContent());
        $firstRecord = data_get($data, 'data.0.id');
        $secondRecord = data_get($data, 'data.1.id');
        $this->assertGreaterThan($secondRecord, $firstRecord);
    }

    public function test_get_workout_sessions_validate_lift_filter_param()
    {
        $this->seed();
        $this->getJson(route('workout-sessions.index', [
            'lift' => 100
        ]))
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'lift' => 'The selected lift is invalid.'
        ]);
    }

    public function test_get_workout_sessions_validate_refs_filter_param()
    {
        $this->getJson(route('workout-sessions.index', [
            'reps' => 'not-a-numeric'
        ]))
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'reps' => 'The reps field must be a number.'
        ]);
    }

    public function test_get_workout_sessions_validate_date_format_filter_params(): void
    {
        $this->getJson(route('workout-sessions.index', [
                'date_from' => 'invalid-from-date',
                'date_to'   => 'invalid-to-date',
            ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'date_from' => [
                    'The date from field must be a valid date.',
                    'The date from field must be a date before date to.',
                    'The date from field must match the format Y-m-d.'
                ],
                'date_to'   => [
                    'The date to field must be a valid date.',
                    'The date to field must be a date after date from.',
                    'The date to field must match the format Y-m-d.'
                ]
            ]);
    }

    public function test_get_workout_sessions_validate_date_range_filter_params(): void
    {
        $this->getJson(route('workout-sessions.index', [
            'date_from' => '2024-04-15',
            'date_to'   => '2024-04-10',
        ]))
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'date_from' => 'The date from field must be a date before date to.',
            'date_to'   => 'The date to field must be a date after date from.'
        ]);
    }
}
