<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceFullResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'images' => $this->photos->pluck('photo'),
            'tags' => $this->tags->pluck('name'),
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'price' => $this->price,
        ];
    }
}
