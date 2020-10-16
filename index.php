<?php
	/**
	 * Created by Denis Diachenko.
	 * email: DeniskasViskas4@gmail.com
	 * Date: 16.10.2020 13:51
	 */

	include_once __DIR__."/src/classes/Walmart.php";
	$config = require __DIR__."/config.php";
	$walmart_client = new Walmart(
		$config['prod']['walmart']['client_id'],
		$config['prod']['walmart']['client_secret'],
		$config['prod']['walmart']['url'],
		$config['prod']['walmart']['channel_type']
	);
	print_r($walmart_client->getToken());