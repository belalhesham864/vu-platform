<?php

namespace App\Http\Resources\Position;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
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
            'company_id' => $this->company_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'work_location' => $this->work_location,
            'salary' => $this->salary,
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'approved_by' => $this->approved_by,
        ];
    }
}
