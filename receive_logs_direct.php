<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// ��������
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
// ����channel�����channel���Թ�������
$channel = $connection->channel();

// ���ܻ������ݷ���֮ǰ���������ߣ���������Ҫȷ�����д��ڣ�Ȼ���ٳ��Դ���������Ϣ��

// ����ֱ���Ľ�����
$channel->exchange_declare('direct_logs', 'direct', false, false, false);
// ��������
$channel->queue_declare('hello', false, false, false, false);
// �����������еİ󶨣�
$channel->queue_bind('hello', 'direct_logs', 'routigKey');

// �ص�����
$callback = function ($msg) {
	echo $msg->body;
};

// ��������������
$channel->basic_consume('hello', '', false, true, false, false, $callback);
// �ж��Ƿ���ڻص�����
while(count($channel->callbacks)) {
	// �˴�Ϊִ�лص�����
	$channel->wait();
}
?>