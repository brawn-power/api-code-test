<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiTest extends TestCase
{

    public $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMTBiZDYzMjIzOTdlZDBhOGMxM2NkZjVjODQ4YjI2NjE0NDM1OGY5M2ZkODI3YjU2NDA5ZTE4YTU0MGQ0NjFjNTM0ODYwMGNhNTgyNWU2YmIiLCJpYXQiOjE3MTMzMjQ0MDEuNjU2NjE0LCJuYmYiOjE3MTMzMjQ0MDEuNjU2NjE3LCJleHAiOjE3NDQ4NjA0MDEuNjUxNTY2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.LnSEn0KL7aOEbSszzsEY6ysQqbxK0Dn-OKU4lThT0QKqsdGZM3_s48OIm623COnO7tXHP6zuUDTNrA3_XYmt9OmTNnRfLkwfbZhMCIHIzFIKHE--YXNTgQVflz1L2nOzcy6nqkP9dVlGJFwPtVYZ1EJM_mk5A6P3Jimn8wFLR8RVJk1ZjEl62W8_ynAf33Gb3aT5VzskrfpC2CchJ-Jan2OJrasvRD9OT5rK8nUGwA1u5FVTffMNCSF9jwzoW0jhCcoxaIZ_IR9em11TC4VwUZtKfvDJhDebK3TJ8QEVAD8dhsvRyAn61hceCVhNKXMFjI4k_AfO14DPV9NYxJaQowupBmbEsqjcdrfGJYbCeA2Ze2V-Rm0ViNo8PBz4ERcpf5Mc8Tk3COXcYlvvY-F73QVarTFePpU7DCbU9QY8TZ3ofVE8J6guI_bAknzYB_GWk-mgHe0pLQ8pLBRripSq1FqAREcIs2edgkM2dmv4WL0eRwT3wyRKvODGjUe6SraSffTQfJfxBPf3iNK40Oih8Hqy61u4Fp0RHw7grxS2OWw-pJNTojCd35dg2u6tmNPC_ozjbFuIRRq8J_L7B74ouw1rsmRpfTiR3ic6bjA59GENjr81jYdrZmNviWaP3rM6ekAD2qiLlkXULH5Kzz76dOS0JpI0LNL9Mi9zOpz320o';

    /**
     * unit test login true.
     */
    public function test_login(): void
    {
        $response = $this->post(
            '/api/auth/login',
            [
                'email' => 'duongdinhcuongviajsc@gmail.com',
                'password' => '123123123'
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message',
            ])->assertJson([
                'data' => [
                    'user' => [
                        'email' => 'duongdinhcuongviajsc@gmail.com',
                    ],
                ],
            ]);
    }

    /**
     * unit test login false.
     */
    public function test_login_error(): void
    {
        $response = $this->post(
            '/api/auth/login',
            [
                'email' => 'duongdinhcuongviajsc2@gmail.com',
                'password' => '123123123'
            ]
        );

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }

    /**
     * unit test lift list.
     */
    public function test_lifts_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->get('/api/lifts/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    /**
     * unit test lift list.
     */
    public function test_lifts_store(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->post('/api/lifts/', [
            "name" => "Weight lifting " . time()
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ]);

        $responseData = $response->json();
        $id = $responseData['data']['id'];
        $this->assertGreaterThan(0, $id);
    }

    /**
     * unit test lift list false.
     */
    public function test_lifts_store_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->post('/api/lifts/', [
            "name" => "Weight lifting"
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors",
            ]);
    }

    /**
     * unit test lift update.
     */
    public function test_lifts_update(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->put('/api/lifts/1', [
            "name" => "Jogging 4"
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ]);
    }

    /**
     * unit test lift update false.
     */
    public function test_lifts_update_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->put('/api/lifts/1', [
            "name" => "Weight lifting"
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors",
            ]);
    }

    /**
     * unit test lift delete.
     */
    public function test_lifts_delete(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/lifts/40');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ])->assertJson([
                'success' => true,
            ]);
    }

    /**
     * unit test lift delete false.
     */
    public function test_lifts_delete_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/lifts/100');

        $response->assertStatus(500)
            ->assertJsonStructure([
                "success",
                "message"
            ]);
    }

    /**
     * unit test workout sessions list.
     */
    public function test_workout_sessions_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->get('/api/workout-sessions/?lift=2&reps=2&dates[]=2024-04-11&dates[]=2024-04-13&page=1&limit=15');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    /**
     * unit test workout sessions store.
     */
    public function test_workout_sessions_store(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->post('/api/workout-sessions/', [
            "start_at" => "2024-04-12 11:18:00",
            "end_at" => "2024-04-12 14:18:00",
            "sets" => [
                [
                    "lift_id" => 2,
                    "reps" => 1,
                    "weight" => 40,
                    "order" => 1
                ]
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ]);

        $responseData = $response->json();
        $id = $responseData['data']['id'];
        $this->assertGreaterThan(0, $id);
    }

    /**
     * unit test workout sessions store false.
     */
    public function test_workout_sessions_store_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->post('/api/workout-sessions/', [
            "start_at" => "2024-04-12 11:18:00",
            "end_at" => "2024-04-12 14:18:00",
            "sets" => [
                [
                    "lift_id" => 'ab',
                    "reps" => 1,
                    "weight" => 40,
                    "order" => 1
                ]
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors",
            ]);
    }

    /**
     * unit test workout sessions update.
     */
    public function test_workout_sessions_update(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->put('/api/workout-sessions/6', [
            "start_at" => "2024-04-12 11:18:00",
            "end_at" => "2024-04-13 14:18:00",
            "sets" => [
                [
                    "lift_id" => 2,
                    "reps" => 1,
                    "weight" => 40,
                    "order" => 1
                ]
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ]);

        $responseData = $response->json();
        $id = $responseData['data']['id'];
        $this->assertGreaterThan(0, $id);
    }

    /**
     * unit test workout sessions update false.
     */
    public function test_workout_sessions_update_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->put('/api/workout-sessions/6', [
            "start_at" => "2024-04-12 11:18:00",
            "end_at" => "2024-04-13 14:18:00",
            "sets" => [
                [
                    "lift_id" => 'abc',
                    "reps" => 1,
                    "weight" => 40,
                    "order" => 1
                ]
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                "message",
                "errors",
            ]);
    }

    /**
     * unit test workout sessions delete.
     */
    public function test_workout_sessions_delete(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/workout-sessions/40');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ])->assertJson([
                'success' => true,
            ]);
    }

    /**
     * unit test workout sessions delete false.
     */
    public function test_workout_sessions_delete_error(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/workout-sessions/170');

        $response->assertStatus(500)
            ->assertJsonStructure([
                "success",
                "message"
            ]);
    }
}
