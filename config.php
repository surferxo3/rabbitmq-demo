<?php

/*#############################
* Developer: Mohammad Sharaf Ali
* Description: Global Script Settings
* Date: 20-08-2016
*/#############################

##################### CONSTANTS #####################
defined('HOST')          OR define('HOST', '192.168.0.104');
defined('PORT')          OR define('PORT', '5672');
defined('USER')          OR define('USER', 'msharaf');
defined('PASS')          OR define('PASS', 'msharaf');
defined('VHOST')         OR define('VHOST', '/');
defined('EXCHANGE_TYPE') OR define('EXCHANGE_TYPE', 'direct'); // direct, fanout, headers, topic
defined('CONTENT_TYPE')  OR define('CONTENT_TYPE', 'text/plain');
defined('DEMO_TYPE')     OR define('DEMO_TYPE', '2'); // 1 => basic_consume(), 2 => basic_get()


##################### HELPER METHODS #####################
function process($message)
{
    if (!is_null($message)) {
    	toScreen($message->body);
    	$message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }
}

function closeConn()
{
	global $channel, $connection;

	if (!is_null($channel) && !is_null($connection)) {
		$channel->close();
		$connection->close();
	}

	echo 'Script ends...<br />';
}
register_shutdown_function('closeConn');

function toScreen($data, $exit = 0, $escape = 0) {
	echo '<pre>';
	if (is_array($data)) {
		print_r($data);
	} else {
		if ($escape) {
			echo nl2br($data);
		} else {
			echo $data;	
		}
	}
	echo '<pre>';

	ob_flush();
	flush();

	if ($exit) {
		exit('Script terminated...<br />');
	}
}
