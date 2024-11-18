<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    /** @use HasFactory<PostFactory> */
    use HasFactory;

    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Атрибуты, которые нельзя массово присваивать.
     *
     * @var array
     */
    protected $guarded = [];
}
