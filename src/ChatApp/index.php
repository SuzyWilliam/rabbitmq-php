#!/usr/bin/php
<?php
stream_set_blocking(STDIN, false);
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . "/../vendor/autoload.php";

$connection = new AMQPStreamConnection('rabbitmq', 5672, "guest", "guest");
$channel = $connection->channel();

$channel->exchange_declare('chat_exchange', AMQPExchangeType::FANOUT, false, true, true);

list($queue_name,,) = $channel->queue_declare('', false, false, true, false);
$channel->queue_bind($queue_name, 'chat_exchange', '');

echo " [*] Waiting for logs. To exit press CTRL+C\n";

$channel->basic_consume($queue_name, '', false, true, false, false, function (AMQPMessage $msg) {
    echo ' [x] Recieved: ', $msg->body;
});


$data = fread(STDIN,1028);

while ($data !== "" || $channel->is_consuming()) {
    if ($data !== "") {
        $msg = new AMQPMessage($data);
        
        $channel->basic_publish($msg, 'chat_exchange');
        // echo ' [x] Sent: ', $data, "\n";
    }   
    $data = fread(STDIN,1028);
    $channel->wait(null,true);
}
$channel->close();
$connection->close();
