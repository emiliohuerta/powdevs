<?php

class CurlComponent extends Component {
    
    // this values could be received on the contructor to be custom config
    var $curlLog = "curl";
    var $curlLogError = 'curl-error';
    var $connectionTimeoutDefault = 30;

    public function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
    }

    public function get($url,  $params = [], $timeout = null) {

        $url .= (is_array($params) && !empty($params))? $this->arrayToQueryString($params) : '';
        
        $options = array(
			CURLOPT_HTTPHEADER => array("Accept: application/json"),
			CURLOPT_URL => $url
		);
		
		if(!empty($timeout)){
			$options[CURLOPT_CONNECTTIMEOUT] = $timeout;
			$options[CURLOPT_TIMEOUT] = $timeout;
		}

		return $this->do_request(null, $options,false);

	}

    private function do_request($url, $options) {
		try {
			list($response,$curl) = $this->createConfigAndExecute($url, $options);

			$this->http_response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if (curl_errno($curl) > 0) {
				$message = "CURL respondió un error: " . curl_error($curl) . " errno: " . curl_errno($curl);
				$this->log($message, $this->logError);
				throw new Exception($message, curl_errno($curl));
			} else if ($this->http_response_code >= 400 && json_last_error() !== 0) {
				$message = "El sitio respondió un error: «" . $response  . "». HTTP response: " . $this->http_response_code;
				$this->log($message, $this->logError);
				throw new Exception($message, $this->http_response_code);
			}

			$this->log("Successfully executed", $this->curlLog);
			$this->log("HTTP Response: {$this->http_response_code}", $this->curlLog);
			
            if (strlen($response) < 10000) {
				$this->log("Response: {$response}", $this->curlLog);
			} else {
				$this->log("Too long response to log", $this->curlLog);
			}

			$response = json_decode($response, true);
			if( is_numeric( $response ) || is_string( $response ) ){
				return $response;
			}
			$response['code'] = $this->http_response_code;
			return $response;
		} finally {
			if (gettype($curl) !== "unknown type") {
				curl_close($curl);
			}
		}
	}

    private function createConfigAndExecute($url, $options) {

		$curl = curl_init($url);

		$options[CURLOPT_RETURNTRANSFER] = true;

        if (empty($options[CURLOPT_CONNECTTIMEOUT])){
            $options[CURLOPT_CONNECTTIMEOUT] = $this->connectionTimeoutDefault;
        }
        if (empty($options[CURLOPT_TIMEOUT])) {
            $options[CURLOPT_TIMEOUT] = $this->connectionTimeoutDefault;
        }

		if ($this->skip_verify_ssl) {
			$options[CURLOPT_SSL_VERIFYHOST] = 0;
			$options[CURLOPT_SSL_VERIFYPEER] = 0;
		}
        
        $endpoint = ( empty($url)?$options[CURLOPT_URL]??null:$url);        
		curl_setopt_array($curl, $options);

		$this->log("========================================================", $this->curlLog);
		$this->log("Executing CURL:", $this->curlLog);
		$this->log("URL: {$endpoint}", $this->curlLog);
		$this->log("Options: " . print_r($options, true), $this->curlLog);

		$response = curl_exec($curl);
		return [$response,$curl];
	}

    private function arrayToQueryString(array $data, $options = []) {		
        $string = '?';
		foreach ($data as $key => $value) {
			if(isset($options['urlencode']))
				$value = ($options['urlencode'])? urlencode($value) : $value;
			
			$string .= $key . '=' . $value . '&';
		}
		return rtrim($string, '&');
	}


}

?>