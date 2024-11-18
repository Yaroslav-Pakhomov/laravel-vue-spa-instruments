<?php

declare(strict_types=1);

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $title
 * @property mixed $description
 * @property mixed $image_url
 */
class PostResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'image_url'   => $this->image_url,
        ];
    }
}
