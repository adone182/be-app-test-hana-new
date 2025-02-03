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
            'user_id' => $this->user_id,
            'user_email' => $this->user_email,
            'user_fullname' => $this->user_fullname,
            'user_nohp' => $this->user_nohp,
            'user_status' => $this->user_status,
            'created_at' => $this->created_at ? $this->created_at->toDateString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateString() : null,
        ];
    }
}
