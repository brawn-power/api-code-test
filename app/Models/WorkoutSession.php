<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function search($request){
        $dates = $request->dates;
        $startDate = $request->dates && count($dates) ? $dates[0] : null;
        $endDate = $request->dates && count($dates) ? $dates[1] : null;
        $liftId = $request->lift;
        $reps = $request->reps;
        $page = $request->page;
        $limit = $request->limit && $request->limit <= config('myconfig.limit') ? $request->limit : config('myconfig.limit');
        $offset = ($page - 1) * $limit;
        [$total, $workoutSessions] = DB::selectResultSets('CALL GetWorkoutSessions(?, ?, ?, ?, ?, ?)', [$startDate, $endDate, $liftId, $reps, $limit, $offset]);
        return [
            'total' => $total[0]->total,
            'workoutSessions' => $workoutSessions
        ];
    }
}
