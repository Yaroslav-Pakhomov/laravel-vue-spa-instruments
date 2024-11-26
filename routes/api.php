<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(PostController::class)->prefix('/post')->name('post.')->group(function () {
    // Все посты
    Route::get('/', 'index')->name('index');

    // Страница поста
    Route::get('/{post}', 'show')->name('show');
});
