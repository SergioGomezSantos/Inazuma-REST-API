<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'playerId' => $this->player_id,
            'version' => $this->version,
            'GP' => $this->GP,
            'TP' => $this->TP,
            'kick' => $this->kick,
            'body' => $this->body,
            'control' => $this->control,
            'guard' => $this->guard,
            'speed' => $this->speed,
            'stamina' => $this->stamina,
            'guts' => $this->guts,
            'freedom' => $this->freedom
        ];
    }
}
