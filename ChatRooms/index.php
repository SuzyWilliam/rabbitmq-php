#!/usr/bin/php
<?php
stream_set_blocking(STDIN, false);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . "/../vendor/autoload.php";

$rooms = [
    "travel" => "topic.travel",
    "books" => "topic.books"
];
// $msg = "enter your room (" . implode(", ", $rooms_name) . "): ";
// $selected_room = readline($msg);
// while ($selected_room == "" || !in_array($selected_room, $rooms_name)) {
//     $selected_room = fwrite(STDERR,$msg,1024);
// }
$binding_keys = array_slice($argv, 1);
$selected_room = $binding_keys[0];

if (empty($binding_keys)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [binding_key]\n");
    exit(1);
}


$connection = new AMQPStreamConnection('localhost', 5672, "guest", "guest");
$channel = $connection->channel();

$channel->exchange_declare('chat_exchange_direct', AMQPExchangeType::DIRECT, false, true, true);


list($queue_name,,) = $channel->queue_declare('', false, false, true, false);

// foreach ($rooms as $key => $value) {
    $channel->queue_bind($queue_name, 'chat_exchange_direct',  $rooms[$selected_room]);
// }


echo " [*] Waiting for logs. To exit press CTRL+C\n";

$channel->basic_consume($queue_name,'', false, true, false, false, function (AMQPMessage $msg) {
    echo ' [x] Recieved: ', $msg->body;
});


$data = fread(STDIN, 1028);

while ($data !== "" || $channel->is_consuming()) {
    if ($data !== "") {
        $msg = new AMQPMessage($data);

        $channel->basic_publish($msg, 'chat_exchange_direct', $rooms[$selected_room]);
        // echo ' [x] Sent: ', $data, "\n";
    }
    $data = fread(STDIN, 1028);
    $channel->wait(null, true);
}
$channel->close();
$connection->close();
