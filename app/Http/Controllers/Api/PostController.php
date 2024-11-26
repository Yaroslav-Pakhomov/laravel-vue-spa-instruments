<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;

class PostController extends Controller {

    /**
     * Все посты
     *
     * @return array
     */
    public function index(): array {
        $posts = Post::all();

        return PostResource::collection($posts)->resolve();
    }

    /**
     * Страница поста
     *
     * @param Post $post
     *
     * @return array
     */
    public function show(Post $post): array {
        return PostResource::make($post)->resolve();
    }

}
