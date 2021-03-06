<?php  


class Twich_Api_Service {

	const TWICH_API_BASE = 'https://api.twitch.tv/helix/';

	private $client_id;


	public function __construct($client_id){
		$this->client_id = $client_id;
	}

	public function api_get($endpoint, $params){
		$api_url = Twich_Api_Service::TWICH_API_BASE . $endpoint . '?';

		foreach($params as $key => $values_array){
			if(!is_array($values_array)){
				$values_array = array($values_array);
			}

			foreach ($values_array as $value) {
				$api_url .= '&' . $key . '=' . urlencode($value);
			}
		}

		$twich_response = wp_remote_get( $api_url, array( 
			'headers' => array(
				'Client-ID' => $this->client_id,
			)
		) );

		return $this->get_response_body($twich_response);
	}


	/**
	 * Makes a POST request to the Disqus API.
	 *
	 * @since     3.0
	 * @param     string $endpoint    The Disqus API secret key.
	 * @param     array  $params      The params to be added to the body.
	 * @return    mixed               The response data.
	 */
	public function api_post( $endpoint, $params ) {
		$api_url = Disqus_Api_Service::DISQUS_API_BASE . $endpoint . '.json?'
			. 'api_secret=' . $this->api_secret
			. '&access_token=' . $this->access_token;

		$dsq_response = wp_remote_post( $api_url, array(
			'body' => $params,
			'headers' => array(
				'Referer' => '', // Unset referer so we can use secret key.
			),
			'method' => 'POST',
		) );

		return $this->get_response_body( $dsq_response );
	}



	public function get_response_body($response){
		if(is_wp_error( $response )){
			$error_message = $response->get_error_message();
			$response = new StdClass();
			$response->code = 2;
			$response->response = $error_message;
		}else{
			$response = json_decode($response['body']);
		}

		return $response;
	}

}