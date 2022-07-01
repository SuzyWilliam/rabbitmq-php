<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__."/../../vendor/autoload.php";

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// $channel->exchange_declare('logs', 'fanout', false, false, false);

list($queue_name,,) = $channel->queue_declare('',false,false,true,false);
$channel->queue_bind($queue_name,'logs','');

echo " [*] Waiting for logs. To exit press CTRL+C\n";

$channel->basic_consume($queue_name,'',false,true,false,false,function($msg){
    echo ' [x] ', $msg->body, "\n";
});

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();