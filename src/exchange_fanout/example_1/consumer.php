<?php

use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;

require_once __DIR__. "/../vendor/autoload.php";


$factory= new AMQPConnectionFactory();
$config = new AMQPConnectionConfig();
$config->setHost('rabbitmq');
$config->setPort(5672);
$config->setUser('guest');
$config->setPassword('guest');

$connection = $factory->create($config);
$channel = $connection->channel();

$channel->queue_declare('emailServiceQueue', false,true,false,false);
$channel->queue_bind('emailServiceQueue',"webAppExchange","");

$callback = function($msg){
    echo $msg->body."\n";
};

$channel->basic_consume('emailServiceQueue','',false,true,false,false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
