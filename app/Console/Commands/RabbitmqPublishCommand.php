<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class RabbitmqPublishCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Использование сервера в качестве Publisher(Издатель)';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void {
        // Соединение с сервисом RabbitMQ
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // $msg = new AMQPMessage('Hello World!');

        // Название обменника
        $exchange_name = 'laravel_instruments';
        // Соединение с обменником
        $channel->exchange_declare($exchange_name, 'direct', false, true, false);

        // Router key с типом direct
        $binding_key = 'admin';
        // Само сообщение
        // $msg = new AMQPMessage('Hello World! ' . $binding_key . '.');
        $data = [
            'title'   => 'Some title',
            'content' => 'Some content',
        ];
        $msg = new AMQPMessage(json_encode($data));
        // Отправка в обменник
        $channel->basic_publish($msg, $exchange_name, $binding_key);

        $binding_key = 'dev';
        $msg = new AMQPMessage('Hello World! ' . $binding_key . '.');
        $channel->basic_publish($msg, $exchange_name, $binding_key);

        $binding_key = 'client';
        $msg = new AMQPMessage('Hello World! ' . $binding_key . '.');
        $channel->basic_publish($msg, $exchange_name, $binding_key);

        // $queue_name = 'hello';
        // $channel->queue_declare($queue_name, false, false, false, false);
        // $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World!'\n";

        // Отсоединение от сервиса RabbitMQ
        $channel->close();
        $connection->close();
    }
}
