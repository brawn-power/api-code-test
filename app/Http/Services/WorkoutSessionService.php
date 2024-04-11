<?php

namespace App\Http\Services;

use App\Models\Set;
use App\Models\WorkoutSession;
use App\Repositories\WorkoutSessionRepository;
use Carbon\Carbon;

class WorkoutSessionService
{
    public function __construct(private WorkoutSessionRepository $workoutSessionRepository)
    {
    }

    public function index($request)
    {
        $query = $this->workoutSessionRepository->filter($request);
        $query = $this->workoutSessionRepository->order($query, 'start_at');
        $query = $this->workoutSessionRepository->paginate($query, $request);

        return $query;
    }

    public function stats($request)
    {
        $workoutSessions = $this->workoutSessionRepository->filter($request)
            ->with([
                'sets',
            ])
            ->get();

        return [
            'max_volume' => $workoutSessions->max('volume'),
            'max_weight' => $workoutSessions->max('max_weight'),
        ];
    }
}
