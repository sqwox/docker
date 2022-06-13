<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('172.19.0.2', 5672, 'guest', 'guest');

$channel = $connection->channel();

$channel->queue_declare('test', false, false, false, false);

echo "  Waiting for messages. To exit press CTRL+C";

$callback = function ($msg) {
	$fields = 'get new que';
    $botId = "2116299485:AAHGH9mTowT6ZfKlDCX8_XP5SMuhbe_LOio";
     file_get_contents("https://api.telegram.org/bot2116299485:AAHGH9mTowT6ZfKlDCX8_XP5SMuhbe_LOio/sendMessage?chat_id=735436019&text=". $msg->body . "1");
};

$channel->basic_consume('test', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();

}

$channel->close();

$connection->close();

?>