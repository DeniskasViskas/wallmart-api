<?php
	/**
	 * Created by Denis Diachenko.
	 * email: DeniskasViskas4@gmail.com
	 * Date: 16.10.2020 13:50
	 */

	class Walmart {
		protected $client_id;
		protected $client_secret;
		protected $base_url;
		protected $channel_type;

		/**
		 * Walmart constructor.
		 * @param $client_id
		 * @param $client_secret
		 * @param $base_url
		 * @param $channel_type
		 */
		public function __construct($client_id, $client_secret, $base_url, $channel_type) {
			$this->client_id = $client_id;
			$this->client_secret = $client_secret;
			$this->base_url = $base_url;
			$this->channel_type = $channel_type;
		}
		/**
		 * @return mixed
		 * token time live 900
		 */
		private function getToken() {
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $this->base_url . "/token",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "grant_type=client_credentials",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = json_decode(curl_exec($curl));
			if (isset($response->access_token)) {
				return $response->access_token;
			}
			return $response;
		}
		/**
		 * @param array $itemData
		 * @param $xml
		 * @return SimpleXMLElement
		 */
		private function array_to_xml($itemData, &$xml) {
			foreach($itemData as $key => $value) {
				if(is_array($value)) {
					if(!is_numeric($key)){
						$sub_node = $xml->addChild("$key");
						$this->array_to_xml($value, $sub_node);
					}
					else{
						$this->array_to_xml($value, $xml);
					}
				}
				else {
					$xml->addChild("$key","$value");
				}
			}
			return $xml->asXML();
		}

		/**
		 * @param string|null $feedId
		 * @param int $offset
		 * @param int $limit
		 * @return mixed
		 */
		public function getFeedList($feedId = null, $offset = 0, $limit = 20) {
			$curl = curl_init();
			$get_params = [
				'offset' => $offset,
				'limit' => $limit,
			];
			if (!is_null($feedId)) {
				$get_params['feedId'] = $feedId;
			}
			$url = $this->base_url . '/feeds?' . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param string $feedId
		 * @param int $offset
		 * @param int $limit
		 * @return mixed
		 */
		public function getFeedDetails($feedId, $offset = 0, $limit = 20) {
			$curl = curl_init();
			$get_params = [
				'offset' => $offset,
				'limit' => $limit,
				'includeDetails'=>'true'
			];
			$url = $this->base_url . '/feeds/'.$feedId."?" . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param string|null $sku
		 * @param null $nextCursor
		 * @param int $offset
		 * @param int $limit
		 * @return mixed
		 */
		public function getItems($sku = null,$nextCursor=null, $offset = 0, $limit = 20) {
			$curl = curl_init();
			$get_params = [
				'offset' => $offset,
				'limit' => $limit,
			];
			if (!is_null($sku)) {
				$get_params['sku'] = $sku;
			}
			if (!is_null($nextCursor)){
				$get_params = [];
				$get_params['nextCursor'] =$nextCursor;
			}
			$url = $this->base_url . '/items?' . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/json",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param $itemArray
		 * @return bool|mixed|string
		 */
		public function bulkAddItem($itemArray){
			$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><MPItemFeed xmlns=\"http://walmart.com/\"></MPItemFeed>");
			$curl = curl_init();
			$get_params = [
				'feedType' => 'item'
			];
			$url = $this->base_url . '/feeds?' . http_build_query($get_params);
			echo $this->array_to_xml($itemArray,$xml);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $this->array_to_xml($itemArray,$xml),
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/xml",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param string|null $sku
		 * @param int $shipNode
		 * @return mixed
		 */
		public function getInventories($sku, $shipNode = null) {
			$curl = curl_init();
			$get_params = [
				'sku' => $sku,
			];
			if (!is_null($shipNode)){
				$get_params['shipNode'] = $shipNode;
			}
			$url = $this->base_url . '/inventory?' . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param $itemArray
		 * @param int $shipNode
		 * @return bool|mixed|string
		 */
		public function bulkInventoryUpdate($itemArray, $shipNode = null){
			$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><InventoryFeed xmlns=\"http://walmart.com/\"></InventoryFeed>");
			$curl = curl_init();
			$get_params = [
				'feedType' => 'inventory',
			];
			if (!is_null($shipNode)){
				$get_params['shipNode'] = $shipNode;
			}
			$url = $this->base_url . '/feeds?' . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $this->array_to_xml($itemArray,$xml),
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/xml",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param string|null $sku
		 * @param int $qty
		 * @param int $shipNode
		 * @return mixed
		 */
		public function updateInventories($sku, $qty, $shipNode = null) {
			$curl = curl_init();
			$data = [
				"sku" => $sku,
				"availabilityCode" => "AC",
				"quantity" => [
					"unit" => "EACH",
					"amount" => $qty
				]
			];
			$get_params = [];
			if (!is_null($shipNode)){
				$get_params = [
					'shipNode' => $shipNode
				];
			}
			$url = $this->base_url . '/inventory?' . http_build_query($get_params);
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "PUT",
				CURLOPT_POSTFIELDS => json_encode($data),
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/json",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param $sku
		 * @return bool|mixed|string
		 */
		public function deleteItem($sku){
			$curl = curl_init();
			$url = $this->base_url . '/items/'.$sku;
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "DELETE",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/json",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param array $filter
		 * @return bool|mixed|string
		 */
		public function getAllReleasedOrders($filter){
			$curl = curl_init();
			$url = $this->base_url . '/orders/released?' . http_build_query($filter);
			echo $url."\n";
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}

		/**
		 * @param string $purchaseOrderId
		 * @return bool|mixed|string
		 */
		public function acknowledgeOrder($purchaseOrderId){
			$curl = curl_init();
			$url = $this->base_url . "/orders/$purchaseOrderId/acknowledge";
			curl_setopt_array($curl, [
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTPHEADER => [
					"Authorization: Basic " . base64_encode($this->client_id . ':' . $this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID:" . uniqid(),
					"WM_SEC.ACCESS_TOKEN:" . $this->getToken(),
					"Content-Type: application/json",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			if (!is_null(json_decode($response))){
				return json_decode($response);
			}
			return $response;
		}


	}
