<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => 'admin',
        ]);

        User::factory(10)->create();
        $this->command->info('Таблица с пользователями загружена данными!');

        $this->call(PostSeeder::class);
        $this->command->info('Таблица с постами загружена данными!');
    }
}
