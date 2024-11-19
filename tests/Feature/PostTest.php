<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase {

    /**
     * Для очищения БД перед запуском тестов
     */
    use RefreshDatabase;

    /**
     * Получение всех постов.
     *
     * @return void
     */
    public function test_get_posts_index(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        Post::factory(5)->create();

        $response = $this->get('/post');

        $this->assertDatabaseCount('posts', 5);

        $response->assertStatus(200);
    }

    /**
     * Получение страницы поста.
     *
     * @return void
     */
    public function test_get_post_show(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        $post = Post::factory()->create();

        $response = $this->get('/post/' . $post->id);

        $this->assertDatabaseCount('posts', 1);

        $response->assertStatus(200);

        // Также проверка на статус 200
        $response->assertOk();
    }
}
