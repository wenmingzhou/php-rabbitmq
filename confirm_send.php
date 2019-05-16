<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'confirm_exchange';
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

//�첽�ص���Ϣȷ��
$channel->set_ack_handler(
    function (AMQPMessage $message) {

        echo "Message acked with content " . $message->body . PHP_EOL;
    }
);
$channel->set_nack_handler(
    function (AMQPMessage $message) {
        echo "Message nacked with content " . $message->body . PHP_EOL;
    }
);

//��Ϊconfirmģʽ
$channel->confirm_select();

$channel->exchange_declare($exchange, 'fanout', false, false, true);
$msg = 'demo';
$message = new AMQPMessage($msg, array('content_type' => 'text/plain'));
$channel->basic_publish($message, $exchange, null, true);
//�����ȴ���Ϣȷ��
$channel->wait_for_pending_acks();

$channel->close();
$connection->close();

?>