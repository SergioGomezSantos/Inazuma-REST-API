<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'formationId' => $this->formation_id,
            'emblemId' => $this->emblem_id,
            'coachId' => $this->coach_id,
            'userId' => $this->user_id,
            'players' => PlayerResource::collection($this->whenLoaded('players'))
        ];
    }
}
