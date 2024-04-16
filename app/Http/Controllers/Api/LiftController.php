<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LiftRequest;
use App\Models\Lift;
use App\Helpers\ApiResponse;

class LiftController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lifts = Lift::all();
        return ApiResponse::success($lifts, '', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LiftRequest $request)
    {
        try {
            $lift = new Lift();
            $lift->name = $request->name;
            $lift->save();
            return ApiResponse::success($lift, 'Lift created successfully', 200);
        } catch (\Exception $e) {
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
    public function update(LiftRequest $request, string $id)
    {
        try {
            $lift = Lift::findOrFail($id);
            $lift->name = $request->name;
            $lift->save();
            return ApiResponse::success($lift, 'Lift update successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lift = Lift::findOrFail($id);
            $lift->delete();
    
            return ApiResponse::success(null, 'Lift deleted successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
