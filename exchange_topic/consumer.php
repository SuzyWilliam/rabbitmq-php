<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

require_once __DIR__."/../vendor/autoload.php";


$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');
$channel = $connection->channel();

list($queue_name,,) = $channel->queue_declare('',false,false,true,false);

$binding_keys = array_slice($argv,1);
echo implode('',$binding_keys) ;

foreach ($binding_keys as $key => $value) {
    $channel->queue_bind($queue_name, "topic_webAppExchange",$value);
}

echo " [*] Waiting for logs. To exit press CTRL+C\n";

$channel->basic_consume($queue_name,"topic_webAppExchange",false, true,false,false,function($msg){
    echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";;
});

while ($channel->is_open()) {
   $channel->wait();
}

$channel->close();
$connection->close();