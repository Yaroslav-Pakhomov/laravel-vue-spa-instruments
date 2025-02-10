<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasHierarchy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Трейт для дочерних/родительских эл.
     */
    use HasHierarchy;

    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * Атрибуты, которые нельзя массово присваивать.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return Collection
     */
    public static function getAllMessages(): Collection
    {
        return self::query()->where('parent_id', null)->orderBy('updated_at', 'DESC')->get();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
