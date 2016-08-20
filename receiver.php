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
$consumer_tag = "dummy_consumer_tag"; // optional or pass empty string

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
$channel = $connection->channel();

if (DEMO_TYPE == 1) {
    #basic_consume(queue_name, cosumer_tag, no_local, no_ack, exclusive, nowait, callback)
	$channel->basic_consume($queue, $consumer_tag, false, false, false, false, 'process');

	#loop as long as the channel has callbacks registered
	while (count($channel->callbacks)) {
	    $channel->wait();
	}

} else if (DEMO_TYPE == 2) {
	$message = $channel->basic_get($queue);

	if (!is_null($message)) {
		toScreen($message->body);
		$channel->basic_ack($message->delivery_info['delivery_tag']);
	}	
} else {
	toScreen('Oops it seems you forgot to set the demo criteria!');
}
