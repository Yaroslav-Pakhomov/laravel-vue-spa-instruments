<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// use Illuminate\Http\Testing\File;

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

    public function test_get_post_create(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        $response = $this->get('/post/create');

        $response->assertStatus(200);
    }

    public function test_get_post_store(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        /**
         * Создание имитации хранилища в рамках данного теста
         * !!! Важно, чтобы диски совпадали при тестировании и загрузке файлов,
         * иначе тестовые файлы будут записываться.
         */
        Storage::fake('local');
        // Имитация загрузки и создания мнимого изображения
        $test_img = UploadedFile::fake()->create('test_img.jpg');
        // 2-ой способ создания файла
        // $file = File::create('my_img.jpg');

        // Создание поста
        $post = Post::factory()->create();
        $post["image_url"] = $test_img;

        $response = $this->post('/post', $post->toArray());

        // Также проверка ответа на статус 200
        $response->assertOk();

        // Проверка БД, что запись создана c текущими данными
        $this->assertDatabaseHas('posts', [
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => 'public/images/main_img/' . $test_img->hashName(),
        ]);

        // Проверка, что один или несколько файлов были сохранены...
        Storage::disk('local')->assertExists(['public/images/main_img/' . $test_img->hashName()]);
        // Проверка на отсутствие файла в реальном хранилище
        Storage::disk('local')->assertMissing($test_img->hashName());
    }
}
