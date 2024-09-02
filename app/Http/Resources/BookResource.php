<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'author' => $this->author,
            'description' => $this->description,
            'category' => [
                'category_id' => $this->category_id,
                'category_name' => $this->category->name,
            ],
            'available' => $this->available,
            'published_at' => $this->published_at->format('Y-m-d'),
            'price' => $this->price,
            'rating' => $this->ratings_avg_rating ? round($this->ratings_avg_rating, 2) : null,
            'reviews' => $this->ratings->sortByDesc('created_at')->values()->toArray(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
