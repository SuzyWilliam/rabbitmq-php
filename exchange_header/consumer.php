<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require_once __DIR__."/../vendor/autoload.php";

$headers = array_slice($argv, 1);
if (empty($headers)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [header1=value1] [header2=value2]\n");
    exit(1);
}
$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');
$channel = $connection->channel();

$exchangeName = 'headers_webAppExchange';
$channel->exchange_declare($exchangeName, AMQPExchangeType::HEADERS);

list($queueName, ,) = $channel->queue_declare('', false, false, true);

$bindArguments = [];
foreach ($headers as $header) {
    list ($key, $value) = explode('=', $header, 2);
    $bindArguments[$key] = $value;
}

$channel->queue_bind($queueName, $exchangeName, '', false, new AMQPTable($bindArguments));

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function (AMQPMessage $message) {
    echo PHP_EOL . ' [x] ', $message->delivery_info['routing_key'], ':', $message->body, "\n";
    echo 'Message headers follows' . PHP_EOL;
    var_dump($message->get('application_headers')->getNativeData());
    echo PHP_EOL;
};

$channel->basic_consume($queueName, '', false, true, true, false, $callback);
while ($channel->is_consuming()) {
    try {
        $channel->wait(null, false, 2);
    } catch (AMQPTimeoutException $exception) {
    }
    echo '*' . PHP_EOL;
}

$channel->close();
$connection->close();