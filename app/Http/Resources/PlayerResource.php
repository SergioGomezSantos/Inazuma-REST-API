<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'fullName' => $this->full_name,
            'position' => $this->position,
            'element' => $this->element,
            'originalTeam' => $this->original_team,
            'image' => $this->image,
            'stats' => StatResource::collection($this->whenLoaded('stats')),
            'techniques' => TechniqueResource::collection($this->whenLoaded('techniques')),
        ];
    }
}
