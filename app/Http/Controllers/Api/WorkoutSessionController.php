<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWorkoutSession;
use App\Http\Resources\WorkoutSessionResource;
use App\Models\Set;
use App\Models\WorkoutSession;
use Illuminate\Http\Request;

class WorkoutSessionController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'lift'      => 'nullable|exists:lifts,id',
            'reps'      => 'nullable|numeric',
            'date_from' => 'nullable|date|required_with:date_to|before:date_to|date_format:Y-m-d',
            'date_to'   => 'nullable|date|required_with:date_from|after:date_from|date_format:Y-m-d',
        ]);

        $workoutSessions = WorkoutSession::with(['user', 'sets' => function ($query) {
            $query->with('lift')
                ->selectRaw('*, (reps * weight) as volume');
        }]);

        $stats = Set::selectRaw('max(weight) as max_weight, max(reps * weight) as max_volume');

        if ($request->has('lift')) {
            $workoutSessions->whereRelation('sets', 'lift_id', $request->lift);
            $stats->where('lift_id', $request->lift);
        }

        if ($request->has('reps')) {
            $workoutSessions->whereRelation('sets', 'reps', $request->reps);
            $stats->where('lift_id', $request->reps);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $workoutSessions->whereBetween('created_at', [$request->date_from, $request->date_to]);
            $stats->whereHas('workoutSession', function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
            });
        }

        $workoutSessions = $workoutSessions->orderByDesc('id')->paginate(15);
        $stats = $stats->get();

        return WorkoutSessionResource::collection($workoutSessions)
            ->additional(['stats' => $stats]);
    }

    public function store(CreateWorkoutSession $request)
    {
        // TODO: Implement store() method.
    }
}
