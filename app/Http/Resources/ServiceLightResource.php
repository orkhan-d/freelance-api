<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceLightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageLink = $this->photos->first() ? $this->photos->first()->photo : null;
        return [
            'id' => $this->id,
            'image' => $imageLink,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'description' => $this->description,
            'price' => $this->price,
            'title' => $this->title,
            'created_at' => $this->created_at,
        ];
    }
}
