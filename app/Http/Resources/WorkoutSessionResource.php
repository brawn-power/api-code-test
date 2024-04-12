<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutSessionResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'start_at'   => $this->start_at,
            'end_at'     => $this->end_at,
            'created_at' => $this->created_at,
            'user'       => new UserResource($this->whenLoaded('user')),
            'sets'       => SetResource::collection($this->whenLoaded('sets')),
        ];
    }
}
