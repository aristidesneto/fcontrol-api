<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'initials' => letter_avatar($this->name),
            'email' => $this->email,
            'status' => $this->status,
            'avatar' => $this->avatar,
            'timezone' => $this->timezone,
            'created_at' => $this->created_at,
        ];
    }
}
