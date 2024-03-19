<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutProgress;
use App\Models\WorkoutSession;

class WorkoutProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // allow nullable so we can then use a Null coalescing operator value as a default
        $validator = Validator::make($request->all(), [
            'lift'  => 'nullable|',
            'reps'  => 'nullable|',
            'dates' => 'nullable|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=> 'Validation errors',
                'data'   => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $from = Carbon::now()->subDays($request->input('timeframe') ?? 1)->format('d-m-Y');
        $to = Carbon::now()->addDay()->format('d-m-Y');
        $reps = $request->input('reps') ?? 1;
        $lifts = $request->input('lifts') ?? 1;

        $workouts = WorkoutSession::whereBetween('start_at', [
            now()->parse($from),
            now()->parse($to),
        ])
        ->orderByDesc('start_at')
        ->paginate(15);

        
        $stats = new WorkoutProgress($workouts);

        $data = [
            'data' => [
                'stats'  => [
                    'max_weight' => $workouts->maxWeight(),
                    'max_volume' => $workouts->maxVolume()
                ],
                'workouts' => WorkoutProgress::collection($workouts)
            ]
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }
}
