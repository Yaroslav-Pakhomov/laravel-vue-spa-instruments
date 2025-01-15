<?php

declare(strict_types=1);

namespace App\Console\Commands;

use ClickHouseDB\Client;
use Illuminate\Console\Command;

class ClickHouseDropDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:drop_db {db_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop DB Clickhouse';

    /**
     * Клиент подключения к БД
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $config = $this->getConfig();

        // Создаем клиента
        $this->client = new Client($config);
        // Проверяем соединение с базой
        $this->client->ping();

        // Отправка запрос
        // Создание БД
        $this->dropDatabase();
    }

    /**
     * Конфигурация подключения
     *
     * @return array
     */
    private function getConfig(): array
    {
        return [

            'host'     => env('CLICKHOUSE_HOST'),
            'port'     => env('CLICKHOUSE_PORT'),
            'username' => env('CLICKHOUSE_USERNAME'),
            'password' => env('CLICKHOUSE_PASSWORD'),
            // 'https' => true
        ];
    }

    /**
     * Создание БД
     *
     * @return void
     */
    private function dropDatabase(): void
    {
        $db_name = $this->argument('db_name') ?: env('CLICKHOUSE_DATABASE');

        $this->client->write('DROP DATABASE IF EXISTS ' . $db_name);
    }
}
