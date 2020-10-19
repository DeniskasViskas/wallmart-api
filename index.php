<?php
	/**
	 * Created by Denis Diachenko.
	 * email: DeniskasViskas4@gmail.com
	 * Date: 16.10.2020 20:43
	 */

	include_once __DIR__ . "/src/classes/Walmart.php";
	$config = require(__DIR__ . '/config.php');
	$walmart_client = new Walmart(
		$config['prod']['walmart']['client_id'],
		$config['prod']['walmart']['client_secret'],
		$config['prod']['walmart']['url'],
		$config['prod']['walmart']['channel_type']
	);
	#SFBR1174
//	print_r($walmart_client->getFeedList());
//	print_r($walmart_client->updateInventories('SFBR1174',1));
//	print_r($walmart_client->getInventories('BMI_9133L_Q'));
	$continue = false;
	$next = null;
	while ($continue){
			$items = $walmart_client->getItems(null,$next,0,20);
			if (isset($items->nextCursor)){
				$next = $items->nextCursor;
			}else{
				$continue = false;
			}
			foreach ($items->ItemResponse as $item){
				echo $item->sku."\n";
			}
	}

	print_r($walmart_client->deleteItem('l124142'));
//	print_r($walmart_client->getItems(null,'AoE/Gjc0QTk1RlpLQ1NESzBTRUxMRVJfT0ZGRVIxOUIyRTI4OTE0RkM0MkNFOUYxMkVDMDZGMTY4QTM5MQ==',0,1));
//	print_r($walmart_client->getFeedDetails('296A9F7F813E4E5B91221A0D6B878B0E@AQMBCgA'));

//	$arr[] = [
//		'MPItemFeedHeader' => [
//			'version' => 3.2,
//			'mart' => 'WALMART_US'
//		],
//		[
//			'MPItem' => [
//				'processMode' => 'REPLACE_ALL',
//				'sku' => 'l124142',
//				'productIdentifiers' => [
//					'productIdentifier' => [
//						'productIdType' => 'UPC',
//						'productId' => '567890987656'
//					]
//				],
//				'MPProduct' => [
//					'SkuUpdate' => 'Yes',
//					'ProductIdUpdate' => 'No',
//					'productName' => 'Hotel Style Premium Hypoallergenic White Goose Down Comforter, White',
//					'category' => [
//						'Home' => [
//							'Bedding' => [
//								'shortDescription' => 'Get cozy under this Hotel Style 330-Thread-Count Down Comforter, filled with responsiblysourced, hypoallergenic, white goose down for the ultimate in softness. Providing generous warmth to any master or guest bedroom, this comforter features a 100% textured dobby cover and a stylish metro dot design. Pairing perfectly with any Hotel Style sheet sets and shams (sold separately), the comforter brings premium softness and luxury to any bedroom. This durable comforter can be used alone or as an insert for your Hotel Style Duvet Cover (sold separately). The 550 power fill natural down gives the comforter a resilient suppleness for feathery fluffiness that maintains its indulgent feel night after night. Sewn-through box construction and a 1" gusset for extra loft adds to the luxury-grade hotel quality of this comforter. Machine wash on cold with mild detergent, tumble dry on low heat with three clean tennis balls until thoroughly dry. Do not dry clean. Available in queen and king sizes.',
//								'brand' => 'Saffron Fabs',
//								'manufacturer' => 'Saffron Fabs',
//								'manufacturerPartNumber' => '12313',
//								'multipackQuantity' => 1,
//								'mainImageUrl' => 'http://example.com/lf/71192763.jpg',
//								'smallPartsWarnings' => [
//									'smallPartsWarning' => 0
//								],
//								'requiresTextileActLabeling' => 'No'
//							]
//						]
//					],
//				],
//				'MPOffer' => [
//					'price' => 999.99,
//					'MinimumAdvertisedPrice' => 999.99,
//					'ShippingWeight' => [
//						'measure' => 3.5,
//						'unit' => 'lb'
//					]
//				]
//			]
//		]
//	];

//	var_dump($walmart_client->bulkAddItem($arr));
