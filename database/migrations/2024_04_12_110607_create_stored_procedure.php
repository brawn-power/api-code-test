<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = <<<SQL
                    CREATE PROCEDURE GetWorkoutSessions(
                        IN startDate DATE,
                        IN endDate DATE,
                        IN liftId INT,
                        IN reps INT,
                        IN _limit INT,
                        IN _offset INT
                    )
                    BEGIN
                    SELECT COUNT(total) total
                FROM 
                    (SELECT
                        COUNT(1) total
                        FROM
                            workout_sessions
                            INNER JOIN sets ON workout_sessions.id = sets.workout_session_id
                            LEFT JOIN users ON users.id = workout_sessions.user_id
                            LEFT JOIN lifts ON lifts.id = sets.lift_id
                        WHERE
                            (startDate IS NULL OR workout_sessions.start_at BETWEEN startDate AND endDate)
                            AND (liftId IS NULL OR sets.lift_id = liftId)
                            AND (reps IS NULL OR sets.reps = reps)
                        GROUP BY workout_sessions.id, sets.reps, sets.lift_id
                    ) arc;
            SELECT
                workout_sessions.id,
                workout_sessions.start_at,
                workout_sessions.end_at,
                users.name,
                lifts.name AS lift_name,
                sets.reps,
                SUM(sets.reps * sets.weight) AS volume
            FROM
                workout_sessions
                INNER JOIN sets ON workout_sessions.id = sets.workout_session_id
                LEFT JOIN users ON users.id = workout_sessions.user_id
                LEFT JOIN lifts ON lifts.id = sets.lift_id
            WHERE
                (startDate IS NULL OR workout_sessions.start_at BETWEEN startDate AND endDate)
                AND (liftId IS NULL OR sets.lift_id = liftId)
                AND (reps IS NULL OR sets.reps = reps)
            GROUP BY
                workout_sessions.id,
                workout_sessions.start_at,
                workout_sessions.end_at,
                users.name,
                sets.reps,
                sets.lift_id,
                lifts.name
            ORDER BY
                workout_sessions.start_at DESC
            LIMIT _offset, _limit;
        END
        SQL;

        DB::unprepared($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $query = "DROP PROCEDURE IF EXISTS GetWorkoutSessions";
        DB::unprepared($query);
    }
};
