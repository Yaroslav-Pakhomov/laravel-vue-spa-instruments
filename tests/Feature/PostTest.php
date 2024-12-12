<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
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
     * Получение всех постов.
     *
     * @return void
     */
    public function test_get_posts_index(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        User::factory()->create();
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

        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->get('/post/' . $post->id);

        $this->assertDatabaseCount('posts', 1);

        // $response->assertStatus(200);
        $response->assertStatus(302);

        // Также проверка на статус 200
        // $response->assertOk();
    }

    /**
     * Получение страницы создание поста.
     *
     * @return void
     */
    public function test_get_post_create(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        $response = $this->get('/post/create');

        $response->assertStatus(200);
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

        $user = User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();
        $post["image_url"] = $test_img;

        $response = $this->actingAs($user)->post('/post', $post->toArray());

        // Также проверка ответа на статус 200
        // $response->assertOk();
        $response->assertStatus(302);

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

        // Утверждает, что ошибок валидации нет
        $response->assertValid();
        // Утверждает, что нет ошибок валидации для указанных ключей
        $response->assertValid(['title', 'description', 'image_url']);
    }

    /**
     * Получение страницы редактирования поста.
     *
     * @return void
     */
    public function test_get_post_edit(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->get('/post/' . $post->id . '/edit');

        $response->assertStatus(200);
    }

    /**
     * Проверка отправки формы/обновление поста в БД.
     *
     * @return void
     */
    public function test_get_post_update(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        // Имитация загрузки и создания мнимого изображения
        $test_img = UploadedFile::fake()->create('test_img.jpg');
        // 2-ой способ создания файла
        // $file = File::create('my_img.jpg');
        $data = [
            'title'       => 'test_title',
            'description' => 'test_description',
            'image_url'   => $test_img,
        ];

        User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();

        $response = $this->patch('/post/' . $post->id, $data);

        // dd($response->status());

        // Также проверка ответа на статус 200
        $response->assertStatus(302);

        // Проверка БД, что запись создана c текущими данными
        $this->assertDatabaseHas('posts', [
            'id'          => (int)$post->id,
            'title'       => 'test_title',
            'description' => 'test_description',
            'image_url'   => 'public/images/main_img/' . $test_img->hashName(),
        ]);

        // Проверка, что один или несколько файлов были сохранены...
        Storage::disk('local')->assertExists(['public/images/main_img/' . $test_img->hashName()]);
        // Проверка на отсутствие файла в реальном хранилище
        Storage::disk('local')->assertMissing($test_img->hashName());

        // Утверждает, что ошибок валидации нет
        $response->assertValid();
        // Утверждает, что нет ошибок валидации для указанных ключей
        $response->assertValid(['title', 'description', 'image_url']);
    }

    /**
     * Проверка отправки формы/удаление поста в БД
     * авторизованным пользователем.
     *
     * @return void
     */
    public function test_get_post_auth_delete(): void {
        /**
         * Для вывода ошибок при выполнении текущего теста
         */
        $this->withoutExceptionHandling();

        // Создание пользователя
        $user = User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();

        // Удаление поста авторизованным пользователем actingAs()
        $response = $this->actingAs($user)->delete('/post/' . $post->id);

        // Также проверка ответа на статус 200
        $response->assertOk();

        // Проверка БД, что запись отсутствует c текущими данными
        $this->assertDatabaseMissing('posts', [
            'id'          => $post->id,
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => $post->image_url,
        ]);
    }

    /**
     * Проверка, что только авторизованный пользователь
     * может удалять
     *
     * @return void
     */
    public function test_get_post_only_auth_delete(): void {
        User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();

        // Удаление поста авторизованным пользователем actingAs()
        $response = $this->delete('/post/' . $post->id);

        $response->assertStatus(302);
        $response->assertRedirect();

        // Проверка БД, что запись остался c текущими данными
        $this->assertDatabaseHas('posts', [
            'id'          => $post->id,
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => $post->image_url,
        ]);
    }

    /**
     * Проверка валидации поля "title"
     * 'required' и 'string'
     *
     * @return void
     */
    public function test_check_validate_title_post(): void {
        /**
         * !!! Важно вызов withoutExceptionHandling() не нужен, т.к. мы
         * проверяем на получение ошибки при отправке некорректных данных
         */

        // -------------------------------------
        // Проверка 'required' - начало
        // -------------------------------------
        User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();
        // Изменение данных для проверки
        $post->title = null;
        $post->image_url = '';

        $response = $this->post('/post', $post->toArray());
        // Код статуса ответа
        // dd($response->status());

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertInvalid('title');
        // -------------------------------------
        // Проверка 'required' - конец
        // -------------------------------------

        // -------------------------------------
        // Проверка 'string' - начало
        // -------------------------------------
        // Создание поста
        $post = Post::factory()->create();
        // Изменение данных для проверки
        $post->title = 1;
        $post->image_url = '';

        $response = $this->post('/post', $post->toArray());

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertInvalid('title');
        // -------------------------------------
        // Проверка 'string' - конец
        // -------------------------------------
    }

    /**
     * Проверка валидации поля "description"
     * 'string'
     *
     * @return void
     */
    public function test_check_validate_description_post(): void {
        /**
         * !!! Важно вызов withoutExceptionHandling() не нужен, т.к. мы
         * проверяем на получение ошибки при отправке некорректных данных
         */

        // -------------------------------------
        // Проверка 'string' - начало
        // -------------------------------------
        User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();
        // Изменение данных для проверки
        $post->description = 1;
        $post->image_url = '';

        $response = $this->post('/post', $post->toArray());

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertInvalid('description');
        // -------------------------------------
        // Проверка 'string' - конец
        // -------------------------------------
    }

    /**
     * Проверка валидации поля "image_url"
     * 'file' и 'mimes'
     *
     * @return void
     */
    public function test_check_validate_image_url_post(): void {
        /**
         * !!! Важно вызов withoutExceptionHandling() не нужен, т.к. мы
         * проверяем на получение ошибки при отправке некорректных данных
         */

        // -------------------------------------
        // Проверка 'file' - начало
        // -------------------------------------
        User::factory()->create();
        // Создание поста
        $post = Post::factory()->create();

        $response = $this->post('/post', $post->toArray());

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertInvalid('image_url');
        // -------------------------------------
        // Проверка 'file' - конец
        // -------------------------------------

        // -------------------------------------
        // Проверка 'mimes' - начало
        // -------------------------------------

        // Имитация загрузки и создания мнимого изображения
        $test_file = UploadedFile::fake()->create('test_file.xlsx');

        // Создание поста
        $post = Post::factory()->create();
        $post->image_url = $test_file;
        // dd($post->toArray());

        $response = $this->post('/post', $post->toArray());
        // Проверка на отсутствие файла в хранилище
        Storage::disk('local')->assertMissing(['public/images/main_img/' . $test_file->hashName()]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertInvalid('image_url');
        // -------------------------------------
        // Проверка 'mimes' - конец
        // -------------------------------------
    }
}
