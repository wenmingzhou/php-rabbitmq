<?php 


require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
	echo " [x] Received ", $msg->body, "\n";
	sleep(2);
	echo " [x] Done".PHP_EOL;
	$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('hello', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
	$channel->wait();
}
$channel->close();
$connection->close();
?>