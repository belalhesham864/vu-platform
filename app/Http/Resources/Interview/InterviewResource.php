<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
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
            'application_id' => $this->application_id,
            'job_title' => $this->application?->position?->title,
            'candidate_name' => $this->application?->candidate?->name,
            'candidate_email' => $this->application?->candidate?->email,
            'candidate_cv' => $this->application?->candidate?->cv_file,
            'interviewer_id' => $this->interviewer_id,
            'available_until' => $this->available_until,
            'estimated_duration' => $this->estimated_duration,
            'question_count' => $this->question_count,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
