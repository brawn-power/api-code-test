<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkoutSession;
use App\Helpers\ApiResponse;
use App\Http\Requests\CreateWorkoutSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkoutSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $total = WorkoutSession::countDatas($request);
        $workoutSessions = WorkoutSession::search($request);
        return ApiResponse::success(['total' => 1, 'workoutSessions' => $workoutSessions], '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWorkoutSession $request)
    {
        DB::beginTransaction();
        try {
            $workoutSession = new WorkoutSession();
            $workoutSession->user_id = Auth::id();
            $workoutSession->start_at = $request->start_at;
            $workoutSession->end_at = $request->end_at;
            $workoutSession->save();

            $workoutSession->sets()->createMany($request->sets);

            DB::commit();
            return ApiResponse::success($workoutSession, 'Workout session created successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateWorkoutSession $request, string $id)
    {

        DB::beginTransaction();
        try {
            $workoutSession = WorkoutSession::findOrFail($id);
            $workoutSession->user_id = Auth::id();
            $workoutSession->start_at = $request->start_at;
            $workoutSession->end_at = $request->end_at;
            $workoutSession->save();
            
            $workoutSession->sets()->delete();
            $workoutSession->sets()->createMany($request->sets);

            DB::commit();
            return ApiResponse::success($workoutSession, 'Workout session updated successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $workoutSession = WorkoutSession::findOrFail($id);
            $workoutSession->sets()->delete();
            $workoutSession->delete();
            DB::commit();
            return ApiResponse::success(null, 'Lift deleted successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
