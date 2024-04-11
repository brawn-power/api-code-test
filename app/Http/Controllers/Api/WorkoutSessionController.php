<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWorkoutSession;
use App\Http\Requests\WorkoutSessionIndex;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\WorkoutSessionResource;
use App\Http\Resources\WorkoutSessionSingleResource;
use App\Http\Services\WorkoutSessionService;

class WorkoutSessionController extends Controller
{
    private $workoutSessionService;

    public function __construct(WorkoutSessionService $workoutSessionService)
    {
        $this->workoutSessionService = $workoutSessionService;
    }

    public function store(CreateWorkoutSession $request)
    {
        // TODO: Implement store() method.
    }

    /**
     * List all workout session
     *
     * @param WorkoutSessionIndex $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(WorkoutSessionIndex $request)
    {
        $filters = $request->merge([
            'user_id' => $request->user()?->id,
        ])->all();
        $data = new PaginateResource(
            WorkoutSessionResource::collection(
                $this->workoutSessionService->index($filters)
            )
        );
        $stats = $this->workoutSessionService->stats($filters);
        return $this->success(array_merge($data->resolve(), [
            'stats' => $stats,
        ]));
    }
}
