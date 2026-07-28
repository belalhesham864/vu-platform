<?php

namespace App\Http\Resources\Stage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stage_name' => $this->stage_name,
            'stage_order' => $this->stage_order,

            'position' => $this->whenLoaded('position', function () {
                return [
                    'id' => $this->position->id,
                    'title' => $this->position->title,
                    'department' => $this->position->category,
                ];
            }),
        ];
    }
}
