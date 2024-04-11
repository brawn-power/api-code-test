<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class WorkoutSession extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'start_at',
        'end_at',
    ];

    /**
     * Appends
     * @var array
     */
    protected $appends = [
        'volume',
        'max_weight',
    ];

    public function getVolumeAttribute()
    {
        return $this->sets->sum('volume');
    }

    public function getMaxWeightAttribute()
    {
        return $this->sets->max('weight');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sets()
    {
        return $this->hasMany(Set::class);
    }
}
