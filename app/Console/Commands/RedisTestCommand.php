<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RedisTestCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void {
        // Создание
        Cache::put('key_test', 'value_test');
        Cache::put('key_test_1', 'value_test_1');

        // Чтение
        $key_test = Cache::get('key_test');

        // Редактирование/Обновление
        Cache::put('key_test', $key_test . '_new');
        $key_test_new = Cache::get('key_test');
        dump($key_test_new);

        // Удаление
        Cache::forget('key_test');
        // Cache::delete('key_test');


        $str = 'string_test';
        // $result = '';
        // if (Cache::has('string_key')) {
        //     $result = Cache::get('string_key');
        // } else {
        //     Cache::put('string_key', $str);
        //     $result = $str;
        // }

        // Аналог записи выше, rememberForever используется без времени (с.)
        $result = Cache::remember('string_key', 60 * 60, function () use ($str) {
            return $str;
        });
        dump($result);

        dd('Redis command');
    }
}
