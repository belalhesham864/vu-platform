<?php

namespace App\Http\Resources\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'candidate_id' => $this->candidate_id,
            'position_id' => $this->position_id,
            'application_type' => $this->application_type,
            'status' => $this->status,
            'decision' => $this->decision,
            'decision_date' => $this->decision_date,
            'start_date' => $this->start_date,
            'approved_by' => $this->approved_by,
        ];
    }
}
