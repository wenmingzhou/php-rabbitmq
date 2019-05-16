<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
// declare  exchange but don`t bind any queue
$channel->exchange_declare('topic_logs', 'topic', false, false, false);

$message = new AMQPMessage("Hello World!");
echo " [x] Sent non-mandatory ...";
$routing_key ="rkey.save";
$channel->basic_publish(
    $message,
    'topic_logs',
	$routing_key
);
echo " done.\n";
$wait = true;
$returnListener = function (
    $replyCode,
    $replyText,
    $exchange,
    $routingKey,
    $message
) use ($wait) {
    $GLOBALS['wait'] = false;
    echo "return: ",
    $replyCode, "\n",
    $replyText, "\n",
    $exchange, "\n",
    $routingKey, "\n",
    $message->body, "\n";
};
$channel->set_return_listener($returnListener);
echo " [x] Sent mandatory ... ";
$routing_key ="rkey2.save";
$channel->basic_publish(
    $message,
    'topic_logs',
    $routing_key,
	true
);
echo " done.\n";
while ($wait) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>