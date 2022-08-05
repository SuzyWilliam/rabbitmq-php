<?php
require_once __DIR__. "/../vendor/autoload.php";

use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$factory = new AMQPConnectionFactory();
$config = new AMQPConnectionConfig();
$config->setHost('rabbitmq');
$config->setPort(5672);
$config->setUser('guest');
$config->setPassword('guest');

//Create connection and channel
$connection = $factory->create($config);
$channel= $connection->channel();



//Create connection and channel
// $connection = new AMQPStreamConnection("localhost",5672,"guest","guest");
// $channel = $connection->channel();


//create exchange type fanout (published to all)
$channel->exchange_declare('webAppExchange',AMQPExchangeType::FANOUT,false, true);

//we can but not required to decleare the queue as it is idempontent - it will only be created if it doesn't exist already
// $channel->queue_declare('emailServiceQueue',false,true,false,false);

//convert body to bytes
$message = new AMQPMessage('Hello World!');
$channel->basic_publish($message,'webAppExchange','',false,false);

$channel->close();
$connection->close();
