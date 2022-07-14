<?php
//https://github.com/php-amqplib/php-amqplib/pull/735/files/100137484307a33d19e40243d131882ebf6bab05#diff-558cbc283463bf49ffb8eced2d89fa50ee2b09245222b0c29197335f41862a3d

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

require_once __DIR__."/../vendor/autoload.php";


$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');
$channel = $connection->channel();

$exchangeName = 'headers_webAppExchange';
$channel->exchange_declare($exchangeName, AMQPExchangeType::HEADERS);

$data = implode(' ', array_slice($argv, 2));
if (empty($data)) {
    $data = "Hello World!";
}

$message = new AMQPMessage($data);
$headers = new AMQPTable(array(
    'subject' => "tour",
    "action" => "canceled"
));

$headers->set('shortshort', -5, AMQPTable::T_INT_SHORTSHORT);
$headers->set('short', -1024, AMQPTable::T_INT_SHORT);

echo PHP_EOL . PHP_EOL . 'SENDING MESSAGE WITH HEADERS' . PHP_EOL . PHP_EOL;
var_dump($headers->getNativeData());
echo PHP_EOL;

$message->set('application_headers', $headers);
$channel->basic_publish($message, $exchangeName);

echo ' [x] Sent :', $data, PHP_EOL;

$channel->close();
$connection->close();