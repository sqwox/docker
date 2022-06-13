<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
//отправка
//устанавливаем соединение с брокером
$connection = new AMQPStreamConnection('172.19.0.2', 5672, 'guest', 'guest');

//открываем канал
$channel = $connection->channel();


//получаем очередь 'test' если нету такой то создаем
$channel->queue_declare('test', false, false, false, false);


//создаем соообщение
$msg = new AMQPMessage('test45343453');

//помещаем его в очередь
$channel->basic_publish($msg, '', 'test');
echo "send12!";

//закрываем канал
$channel->close();

//отключаемся от брокера
$connection->close();