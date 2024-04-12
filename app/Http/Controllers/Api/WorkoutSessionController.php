<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWorkoutSession;
use App\Http\Requests\ListWorkoutSession;

class WorkoutSessionController extends Controller
{
    public function index(ListWorkoutSession $request)
    {
        $workoutSessions = $request->user()->workoutSessions()
            ->with(['sets' => function ($query) use ($request) {
                $query->filter($request->only('lift_id', 'reps'));
            }])
            ->whereHas('sets', function ($query) use ($request) {
                $query->filter($request->only('lift_id', 'reps'));
            })
            ->when(isset($request->date_from) && isset($request->date_to), function ($query) use ($request) {
                $query->whereDate('start_at', '>=', $request->date_from)
                    ->whereDate('end_at', '<=', $request->date_to);
            })
            ->orderBy('start_at', 'desc')
            ->paginate();

        return response()->json([
            'max_weight' => $request->user()->sets()->max('weight'),
            'workout_sessions' => $workoutSessions,
        ]);
    }

    public function store(CreateWorkoutSession $request)
    {
        // TODO: Implement store() method.
    }
}
