<?php

namespace App\Models;

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

    /**
     * Appends
     * @var array
     */
    protected $appends = [
        'volume',
    ];

    public function getVolumeAttribute()
    {
        return $this->reps * $this->weight;
    }

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
}
