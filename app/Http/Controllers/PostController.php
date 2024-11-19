<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Inertia\Response;
use Inertia\ResponseFactory;

class PostController extends Controller {

    /**
     * Все посты
     *
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory {
        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();

        return inertia("Post/Index", compact('posts'));
    }


    /**
     * Страница поста
     *
     * @param Post $post
     *
     * @return Response|ResponseFactory
     */
    public function show(Post $post): Response|ResponseFactory {
        $post = PostResource::make($post)->resolve();

        return inertia("Post/Show", compact('post'));
    }
}
