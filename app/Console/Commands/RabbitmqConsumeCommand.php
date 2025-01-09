<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConsumeCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Использование сервера в качестве Consumer (Потребитель)';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void {
        // Соединение с сервисом RabbitMQ
        // 'rabbitmq', 5672, 'guest', 'guest'
        $connection = new AMQPStreamConnection(
            Config::get('rabbitmq.host'),
            Config::get('rabbitmq.port'),
            Config::get('rabbitmq.user'),
            Config::get('rabbitmq.password'),
        );
        $channel = $connection->channel();

        // $channel->queue_declare('hello', false, false, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        // Обменник
        $exchange_name = 'laravel_instruments';

        // -------------------------------
        // Очередь "admin" - начало
        // -------------------------------
        // Очередь
        $queue_name = 'instruments_admin';
        // Router key с типом direct
        $binding_key = 'admin';
        // Привязка очереди к обменнику по router key
        $channel->queue_bind($queue_name, $exchange_name, $binding_key);

        // Обработка полученного сообщения по нужно логике
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            // Здесь можно обратиться к Моделям, Заданию(Job) для написания логики и т.д.
            print_r($data);
            // echo ' [x] Received ', $msg->body, "\n";
        };

        // Исполнение логики
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        // -------------------------------
        // Очередь "admin" - конец
        // -------------------------------

        // -------------------------------
        // Очередь "dev" - начало
        // -------------------------------
        $queue_name = 'instruments_dev';
        $binding_key = 'dev';
        $channel->queue_bind($queue_name, $exchange_name, $binding_key);

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        // -------------------------------
        // Очередь "dev" - конец
        // -------------------------------

        // -------------------------------
        // Очередь "client" - начало
        // -------------------------------
        $queue_name = 'instruments_client';
        $binding_key = 'client';
        $channel->queue_bind($queue_name, $exchange_name, $binding_key);

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        // -------------------------------
        // Очередь "client" - конец
        // -------------------------------

        // $channel->basic_consume('hello', '', false, true, false, false, $callback);


        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
