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

		public function __construct($client_id, $client_secret,$base_url,$channel_type) {
			$this->client_id = $client_id;
			$this->client_secret = $client_secret;
			$this->base_url = $base_url;
			$this->channel_type = $channel_type;
		}
		public function getToken(){
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

		private function getClientSignature($url, $request_type,$timestamp) {
			// Your walmart info from walmart admin
//			$timestamp = $this->getTime();
			$walmart_secret = $this->client_secret;
			$walmart_consuer_id = $this->client_id;
			// Get an openssl usable private key from the walmart supplied secret
			$pem = $this->pkcs8_to_pem(base64_encode($walmart_secret));
			var_dump($pem);
			$private_key = openssl_pkey_get_private($pem);
			var_dump($private_key);
			// Construct the data we want to sign
			$data = $walmart_consuer_id . "\n";
			$data .= $url . "\n";
			$data .= $request_type . "\n";
			$data .= $timestamp . "\n";
			// Sign the data
			$hash = defined("OPENSSL_ALGO_SHA256") ? OPENSSL_ALGO_SHA256 : "sha256";
			if (!openssl_sign($data, $signature, $private_key, $hash)) {
				return null;
			}
			return base64_encode($signature);
		}

		private function pkcs8_to_pem($der) {
			static $BEGIN_MARKER = "-----BEGIN PRIVATE KEY-----";
			static $END_MARKER = "-----END PRIVATE KEY-----";
			$value = base64_encode($der);
			$pem = $BEGIN_MARKER . "\n";
			$pem .= chunk_split($value, 64, "\n");
			$pem .= $END_MARKER . "\n";
			return $pem;
		}

		private function getTime(){
			return round(microtime(true) * 1000);
		}

		public function getFeedList(){
			$url = $this->base_url.'/feeds';
			$req_type = 'GET';
			$timestamp = $this->getTime();
			$ch = curl_init($url);
			curl_setopt_array($ch, [
				CURLOPT_HTTPHEADER=>[
					'Accept: application/json',
					'WM_SVC.NAME: Walmart Marketplace',
					'WM_CONSUMER.ID:'.$this->client_id,
					'WM_SEC.TIMESTAMP:'.$timestamp,
					'WM_SEC.AUTH_SIGNATURE:'.$this->getClientSignature($url,$req_type,$timestamp),
					'WM_QOS.CORRELATION_ID:'.uniqid(),
					'WM_CONSUMER.CHANNEL.TYPE:'.$this->channel_type
				],
				CURLOPT_FAILONERROR=>true,
				CURLOPT_CUSTOMREQUEST=>$req_type,
				CURLOPT_AUTOREFERER=>true,
				CURLOPT_FOLLOWLOCATION=>true,
				CURLOPT_RETURNTRANSFER=>true,
			]);
			$result = curl_exec($ch);
			var_dump($result);
		}
	}
