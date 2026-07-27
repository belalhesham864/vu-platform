<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id'            => $this->id,
            'company_name'  => $this->company_name,
            'slug'          => $this->slug,
            'industry'      => $this->industry,
            'location'      => $this->location,
            'about'         => $this->about,
            'phone'         => $this->phone,
            'logo'          => asset('uploads/'.$this->logo),
            'website'       => $this->website,
            'company_size'  => $this->company_size,
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d h:i A'),
             'owner'        => new UserResource($this->owner),
        ];
    }
}
