<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// use Illuminate\Http\Testing\File;

class PostTest extends TestCase {

    /**
     * Для очищения БД перед запуском тестов
     */
    use RefreshDatabase;

    /**
     * Действия выполненные для всех тестов по умолчанию
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        /**
         * Создание имитации хранилища в рамках данного теста
         * !!! Важно, чтобы диски совпадали при тестировании и загрузке файлов,
         * иначе тестовые файлы будут записываться.
         */
        Storage::fake('local');
    }

    /**
     * Получение всех постов JSON.
     *
     * @return void
     */
    public function test_get_posts_index(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        // Создание постов
        $posts = Post::factory(5)->create();
        // Преобразование коллекции в json для проверки ответа
        $json_posts = $posts->map(function ($post) {
            return [
                'id'          => $post->id,
                'title'       => $post->title,
                'description' => $post->description,
                'image_url'   => $post->image_url,
            ];
        })->toArray();

        $this->assertDatabaseCount('posts', 5);

        $response = $this->get('api/post');

        // Сравнение ответа с созданными данными
        $response->assertJson($json_posts);
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
        // Проверка создания данных в БД
        $this->assertDatabaseCount('posts', 1);

        $response = $this->get('api/post/' . $post->id);

        // Проверка на статус 200
        $response->assertStatus(200)->assertOk()
            // Сравнение ответа с созданными данными
            ->assertJson([
                'id'          => $post->id,
                'title'       => $post->title,
                'description' => $post->description,
                'image_url'   => $post->image_url,
            ]);
    }

}
