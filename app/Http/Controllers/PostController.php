<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
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
        Cache::forget('key_test');
        $posts = Cache::rememberForever('posts:all', function () {
            $posts = Post::all();
            return PostResource::collection($posts)->resolve();
        });

        return inertia("Post/Index", compact('posts'));
    }

    /**
     * Страница поста
     *
     * @param int $id
     *
     * @return Response|ResponseFactory|RedirectResponse
     */
    // public function show(Post $post): Response|ResponseFactory {
    // $post = PostResource::make($post)->resolve();
    public function show(int $id): Response|ResponseFactory|RedirectResponse {
        // Если нет в кэше
        if (!Cache::has("posts:{$id}")) {
            return redirect()->route('post.index');
        }
        $post = Cache::get("posts:{$id}");

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
     * @return Response|ResponseFactory|RedirectResponse
     */
    public function store(PostStoreRequest $request): Response|ResponseFactory|RedirectResponse {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post = Post::query()->create($data);

        $post = Cache::rememberForever('posts:' . $post->id, function () use ($post) {
            return PostResource::make($post)->resolve();
        });

        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();
        Cache::put('posts:all', $posts);

        return redirect()->route('post.show', $post['id']);
    }

    /**
     * Метод для асинхронного запроса axios
     *
     * @param PostStoreRequest $request
     *
     * @return JsonResponse
     */
    public function storePost(PostStoreRequest $request): JsonResponse {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post = Post::query()->create($data);

        $post = Cache::rememberForever('posts:' . $post->id, function () use ($post) {
            return PostResource::make($post)->resolve();
        });

        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();
        Cache::put('posts:all', $posts);

        return response()->json($post);
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
     * @return Response|ResponseFactory|RedirectResponse
     */
    public function update(PostUpdateRequest $request, Post $post): Response|ResponseFactory|RedirectResponse {
        $data = $request->validated();

        if (!empty($data["image_url"])) {
            $data["image_url"] = Storage::disk('local')->put('public/images/main_img', $data["image_url"]);
        }
        $post->update($data);
        $post = PostResource::make($post)->resolve();

        Cache::put('posts:' . $post['id'], $post);

        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();
        Cache::put('posts:all', $posts);

        return redirect()->route('post.show', $post['id']);
    }

    /**
     * Форма удаление поста в БД
     *
     * @param Post $post
     *
     * @return Response|ResponseFactory
     */
    public function delete(Post $post): Response|ResponseFactory {
        $post->delete();
        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();

        // Если есть в кэше
        if (Cache::has("posts:{$post->id}")) {
            Cache::forget("posts:{$post->id}");
        }

        return inertia("Post/Index", compact('posts'));
    }
}
