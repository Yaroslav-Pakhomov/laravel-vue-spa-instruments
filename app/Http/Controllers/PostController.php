<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
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
     * Страница создания поста
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

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post = Post::query()->create($data);
        $post = PostResource::make($post)->resolve();

        return inertia("Post/Show", compact('post'));
    }

    /**
     * Страница редактирования поста
     *
     * @param Post $post
     *
     * @return Response|ResponseFactory
     */
    public function edit(Post $post): Response|ResponseFactory {
        return inertia("Post/Edit", compact('post'));
    }

    /**
     * Форма обновления поста в БД
     *
     * @param PostUpdateRequest $request
     * @param Post              $post
     *
     * @return Response|ResponseFactory
     */
    public function update(PostUpdateRequest $request, Post $post): Response|ResponseFactory {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post->update($data);
        $post = PostResource::make($post)->resolve();

        return inertia("Post/Show", compact('post'));
    }
}
