<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__. "/../vendor/autoload.php";

$connection = new AMQPStreamConnection('localhost','5672','guest','guest');
$channel = $connection->channel();

$channel->queue_declare('hello',false,false,false,false);
echo " [*] Waiting for messages. To exit press CTRL+C\n";

$channel->basic_consume('hello','',false,true,FALSE,false,function($msg){
    echo ' [x] Received ', $msg->body, "\n";
});

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();