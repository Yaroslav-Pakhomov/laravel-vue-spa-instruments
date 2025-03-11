<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\SendLikeRequest;
use App\Models\User;
use Inertia\Response;
use Inertia\ResponseFactory;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return Response|ResponseFactory
     */
    public function show(User $user): Response|ResponseFactory
    {
        return inertia('User/Show', compact('user'));
    }

    public function sendLike(SendLikeRequest $request, User $user): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $likeStr = 'Лайк поставил пользователь с ID ' . $validated['from_id'];
        return response()->json(['validated' => $validated, 'user' => $user, 'like_str' => $likeStr]);
    }
}
