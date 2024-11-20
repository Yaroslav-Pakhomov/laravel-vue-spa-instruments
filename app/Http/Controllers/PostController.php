<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStoreRequest;
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

    /**
     * Страница поста
     *
     * @return Response|ResponseFactory
     */
    public function create(): Response|ResponseFactory {
        return inertia("Post/Create");
    }

    /**
     * Форма создания поста в БД
     *
     * @param PostStoreRequest $request
     *
     * @return Response|ResponseFactory
     */
    public function store(PostStoreRequest $request): Response|ResponseFactory {
        $data = $request->validated();
        $post = Post::query()->create($data);
        $post = PostResource::make($post)->resolve();

        return inertia("Post/Show", compact('post'));
    }
}
