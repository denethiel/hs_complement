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