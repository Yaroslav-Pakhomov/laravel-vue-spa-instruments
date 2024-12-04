<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class PostSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        Post::factory(150)->create();

        // Кэширование каждого поста по ID
        Post::all()->each(function (Post $post) {
            Cache::put('posts:' . $post->id, $post);
        });
    }
}
