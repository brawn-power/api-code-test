<?php

namespace Tests\Unit;

use Tests\TestCase;

class ApiTest extends TestCase
{

    public $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMWViZjVjMDA5MGI2MTkyMWY3YmJiYWIwMzdmYmY2NTc3YmZmMDBkNWExYTBhM2VlNTcwYzliYTM2OTRjOWE2ZjU4OGE3ZjRlMjRiNTM2ZWQiLCJpYXQiOjE3MTMyNjM4ODMuMzUyNzM1LCJuYmYiOjE3MTMyNjM4ODMuMzUyNzM4LCJleHAiOjE3NDQ3OTk4ODMuMTkzNzUyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.VVCnjEbWaLKGMkDC4m3nuyk9p9-zKmgps3MZ_C5DDkG5vM2ytS5Vxk6TEEVjz6jvUkvpg4ORe8kAkH1wG1xv3SyD62bj6TW4BDPz5SUhdfPla7x1dTaB1rF4kfNTEBmff6wOoPXs4llQIKPz722JZOk_bSwrpL8iotxVlOBFmgsifAIeApoJmmI1e34KPvdjOqOlSAWQNi2UWbtbROh3oSH24lB-5X4ny5_oslNf15ovfcC_jWF7DP1CCBq1nH1p3PKqUnLynAqr_Sd2zHRzBP8VZbE3kzh5IhGCJdMUq_JDLTYn8HWI_RV1Y0p9bJAQz_yoBGuu4k6HIEXgDWi0MP2alp4YxdU9M39GC24ZG0oMxTBbraKiAkuOvw01qfO3AVejQ94n_6nBareZFG2OaABzIjU7GDu3_75mhL3c0VedCSVRCuxqGsR3Va3H15ySJ-jcKvp6trIJqX5TV-s19TqacM1Y9_B1aOIStJ_GpUs0WWL_eSXGh3LWQQIS-c8O3srvWqIoB0kehxrkyGMvSBpyNY6lc1rLchltLPoiWvsVnAba6FD3YhfEBDFurBgxrIDdAfzR8FbqmxhXQy6Ai0hGUVhdAYB5IRgYqfC0adKFW6PrfbTLJ0GCj0p_oozF2gsk6TXJVOlIzfjowOcjzTnbekzVa69v2f1BHywlDcE';

    /**
     * unit test login.
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

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
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
     * unit test lift delete.
     */
    public function test_lifts_delete(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/lifts/17');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
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
     * unit test workout sessions list.
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

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
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
    }

    /**
     * unit test workout sessions delete.
     */
    public function test_workout_sessions_delete(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Accept' => 'application/json',
        ])->delete('/api/workout-sessions/17');

        $response->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "data",
                "message"
            ]);
    }
}
