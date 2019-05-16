<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// 创建连接
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
// 创建channel，多个channel可以共用连接
$channel = $connection->channel();

// 创建交换机以及队列（如果已经存在，不需要重新再次创建并且绑定）

// 创建直连的交换机
$channel->exchange_declare('direct_logs', 'direct', false, false, false);
// 创建队列
$channel->queue_declare('hello', false, false, false, false);
// 交换机跟队列的绑定，
$channel->queue_bind('hello', 'direct_logs', 'routigKey');

$title ="hello RabbitMq\n";
// 设置消息bady传送字符串logs(消息只能为字符串，建议消息均json格式)
$msg = new AMQPMessage($title);
echo "$title\n";
// 发送数据到对应的交换机direct_logs并设置对应的routigKey
$channel->basic_publish($msg, 'direct_logs', 'routigKey');

$channel->close();
$connection->close();
?>