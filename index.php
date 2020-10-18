<?php
	/**
	 * Created by Denis Diachenko.
	 * email: DeniskasViskas4@gmail.com
	 * Date: 16.10.2020 20:43
	 */

	include_once __DIR__."/src/classes/Walmart.php";
	$config = require(__DIR__.'/config.php');
	$walmart_client = new Walmart(
		$config['test']['walmart']['client_id'],
		$config['test']['walmart']['client_secret'],
		$config['test']['walmart']['url'],
		$config['test']['walmart']['channel_type']
	);
	#SFBR1174
//	print_r($walmart_client->updateInventories('SFBR1174',1));
//	print_r($walmart_client->getInventories('SFBR1174'));
//	print_r($walmart_client->getItems());
