<?php 
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// ��������
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
// ����channel�����channel���Թ�������
$channel = $connection->channel();

// �����������Լ����У�����Ѿ����ڣ�����Ҫ�����ٴδ������Ұ󶨣�

// ����ֱ���Ľ�����
$channel->exchange_declare('direct_logs', 'direct', false, false, false);
// ��������
$channel->queue_declare('hello', false, false, false, false);
// �����������еİ󶨣�
$channel->queue_bind('hello', 'direct_logs', 'routigKey');

$title ="hello RabbitMq\n";
// ������Ϣbady�����ַ���logs(��Ϣֻ��Ϊ�ַ�����������Ϣ��json��ʽ)
$msg = new AMQPMessage($title);
echo "$title\n";
// �������ݵ���Ӧ�Ľ�����direct_logs�����ö�Ӧ��routigKey
$channel->basic_publish($msg, 'direct_logs', 'routigKey');

$channel->close();
$connection->close();
?>