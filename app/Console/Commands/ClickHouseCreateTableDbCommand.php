<?php

declare(strict_types=1);

namespace App\Console\Commands;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Illuminate\Console\Command;


class ClickHouseCreateTableDbCommand extends Command
{
    const bool INSERT = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:create_table_db {db_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create DB Clickhouse';

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
        // CLICKHOUSE_DATABASE
        $config = $this->getConfig();

        // Создаем клиента
        $this->client = new Client($config);
        // Проверяем соединение с базой
        $this->client->ping();

        $database_name = $this->argument('db_name') ?: env('CLICKHOUSE_DATABASE');
        $table_name = 'events';

        // Создание табл. БД
        $this->createTable($database_name, $table_name);

        // -------------------------------
        // Вставка данных - начало
        // -------------------------------
        if (self::INSERT) {
            $data = [
                [time(), 'CLICKS', 1, 1234, '192.168.1.11', 'Moscow', 'user_11', ''],
                [time(), 'VIEWS', 1, 1237, '192.168.1.1', 'Torus', 'user_32', 'http://smi2.ru?utm_campaign=CM1'],
                [time(), 'VIEWS', 1, 1237, '192.168.1.1', 'Osborne', 'user_12', 'http://smi2.ru?utm_campaign=CM1'],
                [time(), 'VIEWS', 1, 1237, '192.168.1.1', 'Moscow', 'user_43', 'http://smi2.ru?utm_campaign=CM3'],
            ];
            foreach ($data as $data_info) {
                $this->insertData($database_name, $table_name, $data_info);
            }
        }
        // -------------------------------
        // Вставка данных - конец
        // -------------------------------

        // Вывод запроса по созданию табл. "events" БД "articles"
        // echo $client->showCreateTable('articles.events');

        // Вывод всех данных
        print_r(
            $this->selectData($database_name, $table_name)->rows()
        );
    }

    /**
     * Конфигурация подключения
     *
     * @return array
     */
    private function getConfig(): array
    {
        return [

            'host' => env('CLICKHOUSE_HOST'),
            'port' => env('CLICKHOUSE_PORT'),
            'username' => env('CLICKHOUSE_USERNAME'),
            'password' => env('CLICKHOUSE_PASSWORD'),
            // 'https' => true
        ];
    }

    /**
     * Создание табл. БД
     *
     * @param string $database_name
     * @param string $table_name
     *
     * @return void
     */
    private function createTable(string $database_name, string $table_name): void
    {
        $this->client->write(
            "
            CREATE TABLE IF NOT EXISTS " . $database_name . "." . $table_name . " (
                event_date  Date DEFAULT toDate(event_time),
                event_time  DateTime,
                event_type  Enum8('VIEWS' = 1, 'CLICKS' = 2),
                site_id     Int32,
                article_id  Int32,
                ip          String,
                city        String,
                user_uuid   String,
                referer     String,
                utm         String DEFAULT extractURLParameter(referer, 'utm_campaign')
            ) engine = MergeTree() ORDER BY article_id
        "
        );
    }

    /**
     * Вставка данных
     *
     * @param string $database_name
     * @param string $table_name
     * @param array $data_info
     *
     * @return void
     */
    private function insertData(string $database_name, string $table_name, array $data_info): void
    {
        $this->client->insert(
            $database_name . "." . $table_name,
            [$data_info],
            ['event_time', 'event_type', 'site_id', 'article_id', 'ip', 'city', 'user_uuid', 'referer']
        );
    }

    /**
     * Выборка данных
     *
     * @param string $database_name
     * @param string $table_name
     *
     * @return Statement
     */
    private function selectData(string $database_name, string $table_name): Statement
    {
        return $this->client->select('SELECT * FROM ' . $database_name . '.' . $table_name);
    }
}
