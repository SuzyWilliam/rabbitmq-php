<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

 require_once __DIR__. "/../../vendor/autoload.php";

 $connection = new AMQPStreamConnection('rabbitmq',5672,'guest','guest');
 $channel = $connection->channel();

 $channel->exchange_declare('direct_webAppExchange', AMQPExchangeType::DIRECT, false, true);

 $routing_keys = [
     "booking" => "tour.book",
     "canceling" => "tour.cancel"
 ];

 $action = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'booking';
 $data = implode(' ', array_slice($argv, 2));

 if(empty($data)){
     $action = "booking";
     $data = "booking message";
 }

 $channel->basic_publish(new AMQPMessage($data),'direct_webAppExchange', $routing_keys[$action]);

 echo ' [x] Sent ', $action, ':', $data, "\n";

 $channel->close();
 $connection->close();