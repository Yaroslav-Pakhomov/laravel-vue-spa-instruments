<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Inertia\Response;
use Inertia\ResponseFactory;

class PostController extends Controller {

    public function index(): Response|ResponseFactory {
        $posts = Post::all();
        $posts = PostResource::collection($posts)->resolve();

        return inertia("Post/Index", compact('posts'));
    }
}
