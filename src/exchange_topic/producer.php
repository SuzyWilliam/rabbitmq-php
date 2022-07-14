<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__."/../vendor/autoload.php";


$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');
$channel = $connection->channel();


$channel->exchange_declare('topic_webAppExchange', AMQPExchangeType::TOPIC, false,false,false);

$route_keys = [
    'beforeBooking'=> "tour.booking.before",
    'afterBooking' => "tour.booking.after",
    'canceled' => "tour.booking.canceled",
    'logCanceled' => "log.booking.canceled",
    'logBooking' => "log.booking.event",
];

$action = $argv[1] ?? 'beforeBooking'; 

$data = implode(' ', array_splice($argv,2));
if(empty($data)){
    $data = "Before Booking Event";
}

$msg = New AMQPMessage($data);

$channel->basic_publish($msg,'topic_webAppExchange', $route_keys[$action]);

$channel->close();
$connection->close();