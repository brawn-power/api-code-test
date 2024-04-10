<?php

namespace App\Http\Services;

use App\Models\Set;
use App\Models\WorkoutSession;
use Carbon\Carbon;

class WorkoutSessionService extends BaseService
{
    public function filter($request)
    {
        $query = WorkoutSession::query()
            ->when(!empty($request['user_id']), function ($query) use ($request) {
                $query->where('user_id', $request['user_id']);
            })
            ->when(!empty($request['lift_id']) || !empty($request['reps']), function ($query) use ($request) {
                $query->whereHas('sets', function ($query) use ($request) {
                    $query->when($request['lift_id'], function ($query) use ($request) {
                        $query->where('lift_id', $request['lift_id']);
                    });
                    $query->when($request['reps'], function ($query) use ($request) {
                        $query->where('reps', $request['reps']);
                    });
                });
            })
            ->when(!empty($request['date_from']) && !empty($request['date_to']), function ($query) use ($request) {
                $query->whereBetween('start_at', [$request['date_from'], $request['date_to']]);
            });

        return $query;
    }

    public function index($request)
    {
        $query = $this->filter($request);
        $query = $this->order($query, 'start_at');
        $query = $this->paginate($query, $request);

        return $query;
    }

    public function stats($request)
    {
        $workoutSessions = $this->filter($request)->with([
            'sets',
        ])->get();

        return [
            'max_volume' => $workoutSessions->max('volume'),
            'max_weight' => $workoutSessions->max('max_weight'),
        ];
    }
}
