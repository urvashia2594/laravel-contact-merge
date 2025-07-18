<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MergeContactResource extends JsonResource
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
            'contact_uuid' => $this->contact_uuid,
            'contact_child_uuid' => $this->contact_child_uuid,
            'email' => $this->email,
            'Phone' => $this->Phone,
            'custom_field' => $this->custom_field,
        ];
    }
}
