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
		private function getToken(){
			echo $this->base_url."/token";
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => $this->base_url."/token",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "grant_type=client_credentials",
				CURLOPT_SSL_VERIFYHOST =>false,
				CURLOPT_SSL_VERIFYPEER=>false,
				CURLOPT_HTTPHEADER =>[
					"Authorization: Basic ".base64_encode($this->client_id.':'.$this->client_secret),
					"WM_SVC.NAME: Walmart Marketplace",
					"WM_QOS.CORRELATION_ID: as4213",
					"Content-Type: application/x-www-form-urlencoded",
					"Accept: application/json"
				],
			]);
			$response = curl_exec($curl);
			return json_decode($response);
		}
		public function getFeedList(){

		}
	}
