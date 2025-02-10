<?php

declare(strict_types=1);

namespace App\Http\Resources\Message;

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
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dump($this->children);
        $children = [];
        if (isset($this->children) && $this->children->count() > 0) {
            $this->children->sortByDesc('updated_at');
            foreach ($this->children as $child) {
                // dump($child);
                // $children[] = (new MessageResource($child))->resolve();
                $children[] = MessageResource::make($child)->resolve();
            }
        }
        // dd($children);
        return [
            'id' => $this->id,

            'body' => $this->body,

            'user'     => $this->user,
            // 'parent'   => $this->parent,
            'children' => $children,

            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
