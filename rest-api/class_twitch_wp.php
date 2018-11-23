<?php 

namespace Twitch;

class Twitch_Api
{
	private static $_instance;


	protected static $_client_id;

	protected static $_endpoint = 'https://api.twitch.tv/helix/';

	public function __construct()
	{

	}

	public static function getInstance()
  	{
    if(self::$_instance === null) {
    	self::$_instance = new self;
    }
    return self::$_instance;
  	}

  	public static function setClientId($client_id)
  	{
  		self::$_client_id    = $client_id;
  	}

  	public function api_get($endpoint, $params){
		$api_url = self::$_endpoint . $endpoint . '?';

		foreach($params as $key => $values_array){
			if(!is_array($values_array)){
				$values_array = array($values_array);
			}

			foreach ($values_array as $value) {
				$api_url .= '&' . $key . '=' . urlencode($value);
			}
		}

		$twitch_response = wp_remote_get( $api_url, array( 
			'headers' => array(
				'Client-ID' => self::$_client_id,
			)
		) );

		return $this->get_response_body($twitch_response);
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
		$api_url = self::$_endpoint . $endpoint . '?';

		$twitch_response = wp_remote_post( $api_url, array(
			'body' => $params,
			'headers' => array(
				'Client-ID' => self::$_client_id, // Unset referer so we can use secret key.
			),
			'method' => 'POST',
		) );

		return $this->get_response_body( $twitch_response );
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

 ?>