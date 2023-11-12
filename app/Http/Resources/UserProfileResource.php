<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profile = $this->profile;
        return [
            'avatar' => $profile->avatar,
            'description' => $profile->description,
            'tags' => $profile->tags->pluck('name'),
            'name' => $this->name,
            'email' => $this->email,
            'lastName' => $this->surname,
            'userId' => $this->id,
        ];
    }
}
