<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category->name,
            'price' => $this->price,
        ];

        if (auth()->user()->role->isAdmin()) {
            $resource['created_at'] = $this->created_at;
            $resource['updated_at'] = $this->updated_at;
        }

        return $resource;
    }
}
