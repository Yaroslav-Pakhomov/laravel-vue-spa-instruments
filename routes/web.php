<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::controller(PostController::class)->prefix('/post')->name('post.')->group(function () {
    // Все посты
    Route::get('/', 'index')->name('index');

    // Страница формы создания поста
    Route::get('/create', 'create')->name('create');

    // Создание поста, отправка данных
    Route::post('/', 'store')->name('store');
    Route::post('/store-post', 'storePost')->name('storePost');
    Route::post('/store-post-cache', 'storePostCache')->name('storePostCache');

    // Страница поста
    // Route::get('/{post}', 'show')->name('show');
    Route::get('/{id}', 'show')->name('show');

    // Страница редактирования поста
    Route::get('/{post}/edit', 'edit')->name('edit');

    // Обновление поста, отправка данных
    Route::patch('/{post}', 'update')->name('update');

    // Удаление поста, отправка данных
    Route::delete('/{post}', 'delete')->name('delete')->middleware(['auth']);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('message', MessageController::class)->missing(['destroy']);
    Route::controller(MessageController::class)->prefix('/message')->name('message.')->group(function () {
        // Route::get('/', 'index')->name('index');
        // Route::get('/create', 'create')->name('create');
        // Route::post('/', 'store')->name('store');
        Route::post('/store-async', 'storeAsync')->name('storeAsync');
        //
        // Route::get('/{id}', 'show')->name('show');
        // Route::get('/{post}/edit', 'edit')->name('edit');
        // Route::patch('/{post}', 'update')->name('update');
        // Route::delete('/{post}', 'delete')->name('delete');
    });
});

require __DIR__ . '/auth.php';
