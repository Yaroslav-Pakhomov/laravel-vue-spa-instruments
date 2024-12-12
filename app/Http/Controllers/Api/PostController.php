<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Api\PostStoreRequest;
use App\Http\Requests\Post\Api\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Форма создания поста в БД
     *
     * @param PostStoreRequest $request
     *
     * @return array
     */
    public function store(PostStoreRequest $request): array {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }

        $post = Post::query()->create($data);

        // dd(PostResource::make($post)->resolve());
        return PostResource::make($post)->resolve();
    }

    /**
     * Форма обновления поста в БД
     *
     * @param PostUpdateRequest $request
     * @param Post              $post
     *
     * @return array
     */
    public function update(PostUpdateRequest $request, Post $post): array {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post->update($data);

        return PostResource::make($post)->resolve();
    }

    /**
     * Форма удаление поста в БД
     *
     * @param Post $post
     *
     * @return JsonResponse
     */
    public function delete(Post $post): JsonResponse {
        $post->delete();
        // 1-ый вариант ответа
        // $posts = Post::all();
        // return PostResource::collection($posts)->resolve();

        // 2-ой вариант ответа
        return response()->json([
            'message' => 'Post deleted successfully.',
        ]);
    }
}
