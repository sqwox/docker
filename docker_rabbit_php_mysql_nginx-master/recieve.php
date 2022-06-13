<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if (!$_SERVER['DOCUMENT_ROOT'])
    $_SERVER['DOCUMENT_ROOT'] = __DIR__;

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
echo 'f';
$connection = new AMQPStreamConnection(R_HOST, R_PORT, R_USER_NAME, R_PASSWORD);
$channel = $connection->channel();

$channel->queue_declare('queue_name', false, true, false, false);

/**
 * не отправляем новое сообщение на обработчик, пока он
 * не обработал и не подтвердил предыдущее. Вместо этого
 * направляем сообщение на любой свободный обработчик
 */
$channel->basic_qos(
    null,   #размер предварительной выборки - размер окна предварительнйо выборки в октетах, null означает “без определённого ограничения”
    1,      #количество предварительных выборок - окна предварительных выборок в рамках целого сообщения
    null    #глобальный - global=null означает, что настройки QoS должны применяться для получателей, global=true означает, что настройки QoS должны применяться к каналу
);


$callback = function ($msg) {
    $arBody = json_decode($msg->body, true); // берем данные

    // some code

    /**
     * Acknowledging the message
     */
    $msg->delivery_info['channel']->basic_ack(
        $msg->delivery_info['delivery_tag'] #delivery tag
    );

};
$channel->basic_consume('queue_name', '', false, false, false, false, $callback);
while (count($channel->callbacks)) {
    $channel->wait();
    echo('wait');
}
$channel->close();
$connection->close();
