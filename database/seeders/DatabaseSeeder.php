<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'              => 'Admin',
            'email'             => 'admin@example.com',
            'email_verified_at' => now(),
            'password'          => 'admin',
        ]);

        User::factory()->create([
            'name'              => 'Ivan',
            'email'             => 'ivan@example.com',
            'email_verified_at' => now(),
            'password'          => 'ivan',
        ]);

        User::factory()->create([
            'name'              => 'Anton',
            'email'             => 'anton@example.com',
            'email_verified_at' => now(),
            'password'          => 'anton',
        ]);

        User::factory(10)->create();
        $this->command->info('Таблица с пользователями загружена данными!');

        $this->call(PostSeeder::class);
        $this->command->info('Таблица с постами загружена данными!');

        $this->call(MessageSeeder::class);
        $this->call(MessageSeeder::class);
        $this->command->info('Таблица с сообщениями загружена данными!');
    }
}
