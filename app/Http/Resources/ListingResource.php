<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListingResource extends JsonResource
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
            'title'         => $this->title,
            'bedrooms'      => $this->bedrooms,
            'bathrooms'     => $this->bathrooms,
            'area'          => $this->area,
            'city'          => $this->city,
            'code'          => $this->code,
            'street'        => $this->street,
            'street_number' => $this->street_number,
            'price'         => $this->price,
            'created_at'    => $this->created_at,
            'images'        => $this->whenLoaded('images'),
        ];
    }
}
