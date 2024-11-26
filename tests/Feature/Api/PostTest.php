<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

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

    /**
     * Проверка отправки формы/создание поста в БД.
     *
     * @return void
     */
    public function test_get_post_store(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        // Имитация загрузки и создания мнимого изображения
        $test_img = UploadedFile::fake()->create('test_img.jpg');
        // 2-ой способ создания файла
        // $file = File::create('my_img.jpg');

        // Создание поста
        $post = Post::factory()->create();
        $post["image_url"] = $test_img;

        $response = $this->post('api/post', $post->toArray());

        // dd($post);

        // Проверка БД, что запись создана c текущими данными
        $this->assertDatabaseHas('posts', [
            // 'id'       => $post->id + 1,
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => 'public/images/main_img/' . $test_img->hashName(),
        ]);

        // Проверка, что один или несколько файлов были сохранены...
        Storage::disk('local')->assertExists(['public/images/main_img/' . $test_img->hashName()]);
        // Проверка на отсутствие файла в реальном хранилище
        Storage::disk('local')->assertMissing($test_img->hashName());

        // Также проверка ответа на статус 200
        $response->assertOk()
            // Утверждает, что ошибок валидации нет
            ->assertValid()
            // Утверждает, что нет ошибок валидации для указанных ключей
            ->assertValid(['title', 'description', 'image_url']);

        // Сравнение ответа с созданными данными
        $response->assertJson([
            // 'id'       => $post->id + 1,
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => 'public/images/main_img/' . $test_img->hashName(),
        ]);
    }
}
