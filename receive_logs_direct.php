<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// 创建连接
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
// 创建channel，多个channel可以共用连接
$channel = $connection->channel();

// 可能会在数据发布之前启动消费者，所以我们要确保队列存在，然后再尝试从中消费消息。

// 创建直连的交换机
$channel->exchange_declare('direct_logs', 'direct', false, false, false);
// 创建队列
$channel->queue_declare('hello', false, false, false, false);
// 交换机跟队列的绑定，
$channel->queue_bind('hello', 'direct_logs', 'routigKey');

// 回调函数
$callback = function ($msg) {
	echo $msg->body;
};

// 启动队列消费者
$channel->basic_consume('hello', '', false, true, false, false, $callback);
// 判断是否存在回调函数
while(count($channel->callbacks)) {
	// 此处为执行回调函数
	$channel->wait();
}
?>