<?php

namespace App\Repositories;


use App\Contracts\WorkoutSessionRepositoryInterface;
use App\Models\WorkoutSession;

class WorkoutSessionRepository extends BaseRepository implements WorkoutSessionRepositoryInterface
{
    public function filter($attributes)
    {
        $query = WorkoutSession::query()
            ->when(!empty($attributes['user_id']), function ($query) use ($attributes) {
                $query->where('user_id', $attributes['user_id']);
            })
            ->when(!empty($attributes['lift_id']) || !empty($attributes['reps']), function ($query) use ($attributes) {
                $query->whereHas('sets', function ($query) use ($attributes) {
                    $query->when($attributes['lift_id'], function ($query) use ($attributes) {
                        $query->where('lift_id', $attributes['lift_id']);
                    });
                    $query->when($attributes['reps'], function ($query) use ($attributes) {
                        $query->where('reps', $attributes['reps']);
                    });
                });
            })
            ->when(!empty($attributes['date_from']) && !empty($attributes['date_to']), function ($query) use ($attributes) {
                $query->whereBetween('start_at', [$attributes['date_from'], $attributes['date_to']]);
            });

        return $query;
    }
}