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
        // 15672
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        // $queue_name = 'instruments_admin';
        // $exchange_name = 'laravel_instruments';
        // $binding_key = 'admin';
        // $channel->queue_bind($queue_name, $exchange_name, $binding_key);

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World!'\n";
        // $channel->queue_declare('hello', false, false, false, false);

        $channel->close();
        $connection->close();
    }
}
