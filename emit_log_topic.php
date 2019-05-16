<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// 声明一个topic交换机
$channel->exchange_declare('topic_logs', 'topic', false, false, false);

// 从命令行获取路由键
$routing_key = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous';
$data = implode(' ', array_slice($argv, 2));
if(empty($data)) $data = "Hello World!";

$msg = new AMQPMessage($data);

// 携带路由键发送到topic_logs交换机
$channel->basic_publish($msg, 'topic_logs', $routing_key);

echo " [x] Sent ",$routing_key,':',$data," \n";

$channel->close();
$connection->close();
?>