<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource;

        return [
            'items' => $this->resource->items(),
            'metadata' => [
                'page' => $resource->currentPage(),
                'page_size' => $resource->perPage(),
                'total_page' => $resource->lastPage(),
                'total_count' => $resource->total(),
            ],
        ];
    }
}
