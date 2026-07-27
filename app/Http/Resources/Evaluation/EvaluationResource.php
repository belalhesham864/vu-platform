<?php

namespace App\Http\Resources\Evaluation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
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
            'interview_id' => $this->interview_id,
            'overall_score' => $this->overall_score,
            'strengths' => $this->strengths,
            'weaknesses' => $this->weaknesses,
            'recording_url' => $this->recording_url,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // 'id' => $this->id,
        //     'application_id' => $this->application_id,
        //     'interview_id' => $this->interview_id,
        //     'overall_score' => $this->overall_score,
        //     'weaknesses' => $this->weaknesses,
        //     'strengths' => $this->strengths,
        //     'recording_url' => $this->recording_url,
        //     'notes' => $this->notes,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,

        //     'answers' => EvaluationAnswerResource::collection($this->whenLoaded('answers')),
        //     'application' => new ApplicationResource($this->whenLoaded('application')),
    }
}
