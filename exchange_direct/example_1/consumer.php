<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

require_once __DIR__."/../../vendor/autoload.php";

$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');
$channel = $connection->channel();
$routing_keys = [
    "booking" => "tour.book",
    "canceling" => "tour.cancel"
];

$actions= array_slice($argv, 1)??["booking"];

$channel->exchange_declare('direct_webAppExchange',AMQPExchangeType::DIRECT,false,true);

list($queue_name,,) = $channel->queue_declare('', false,false, true, false);

foreach ($actions as $action) {
    $channel->queue_bind($queue_name,'direct_webAppExchange',$routing_keys[$action]);
}
echo " [*] Waiting for logs. To exit press CTRL+C\n";

$channel->basic_consume($queue_name,'',false,true,false,false,function($msg){
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
});

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();