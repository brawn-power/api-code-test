<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Set extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'workout_session_id',
        'reps',
        'weight',
        'lift_id',
        'order',
    ];

    protected $appends = [
        'volume',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lift()
    {
        return $this->belongsTo(Lift::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workoutSession()
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function getVolumeAttribute()
    {
        return $this->reps * $this->weight;
    }

    public function scopefilter(Builder $query, array $filters = [])
    {
        return $query
        ->when(isset($filters['lift_id']), function ($query) use ($filters) {
            $query->where('sets.lift_id', $filters['lift_id']);
        })->when(isset($filters['reps']), function ($query) use ($filters) {
            $query->where('reps', $filters['reps']);
        });
    }
}
