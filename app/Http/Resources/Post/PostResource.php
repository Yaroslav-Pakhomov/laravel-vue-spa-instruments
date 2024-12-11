<?php

declare(strict_types=1);

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $title
 * @property mixed $description
 * @property mixed $days_for_create
 * @property mixed $image_url
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class PostResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'description'     => $this->description,
            'days_for_create' => $this->days_for_create,
            'image_url'       => $this->image_url,
            'created_at'      => $this->created_at->format('d-m-Y'),
            'updated_at'      => $this->updated_at->format('d-m-Y'),
        ];
    }
}
