<?php

/*#############################
* Developer: Mohammad Sharaf Ali
* Description: Script to publish message
* Date: 20-08-2016
*/#############################

##################### SCRIPT SETTINGS #####################
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1G');
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Karachi');


##################### LIBRARIES #####################
require_once __DIR__. '/config.php';
require_once __DIR__. '/vendor/autoload.php';


##################### NAMESPACES #####################
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


##################### MAIN #####################
$exchange = 'dummy_exchange';
$queue = 'dummp_queue'; 
$routing_key = 'dummy_routing_key'; // optional or don't pass this param

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

#queue_declare(queue_name, passive, durable, exclusive, auto_delete)
$channel->queue_declare($queue, false, true, false, false);

#exchange_declare(exchange_name, type, passive, durable, auto_delete)
$channel->exchange_declare($exchange, EXCHANGE_TYPE, false, true, false);

$channel->queue_bind($queue, $exchange, $routing_key);

#dummy data for testing purpose
$data = 'Message generated at: '. date('d/m/Y H:i:s');

$message = new AMQPMessage($data, array('content_type' => CONTENT_TYPE, 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

$channel->basic_publish($message, $exchange, $routing_key);
