<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);
for($i=0;$i<10;$i++){
	$msg = new AMQPMessage($i);
	$channel->basic_publish($msg, '', 'hello');
	echo " [x] Sent 'Hello World!$i'\n";
}

$channel->close();
$connection->close();

?>