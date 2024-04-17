<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'workout_session_id' => $this->workout_session_id,
            'reps'               => $this->reps,
            'weight'             => $this->weight,
            'volume'             => $this->whenNotNull($this->volume),
            'lift_id'            => $this->lift_id,
            'order'              => $this->order,
            'created_at'         => $this->created_at,
            'lift'               => new LiftResource($this->whenLoaded('lift')),
            'workout_session'    => new WorkoutSessionResource($this->whenLoaded('workout_session')),
        ];
    }
}
