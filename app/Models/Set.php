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

    protected function volume(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => $this->reps * $this->weight,
        );
    }    
}
