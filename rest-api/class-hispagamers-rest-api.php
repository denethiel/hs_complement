<?php 


class Hispagamers_Rest_Api {
	const REST_NAMESPACE = 'hs/v1';

	const TWICH_API_BASE = 'https://api.twitch.tv/helix/';

	const TWITTER_API_BASE = 'https://api.twitter.com/1.1/';

	private $api_service;

	protected $version;

	public function __construct( $version){
		//$this->api_service = $api_service;
		$this->version = $version;
	}

	public function rest_admin_only_permission_callback( WP_REST_Request $request ) {
        // Regular cookie-based authentication.
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        return $this->rest_get_error( 'You must be logged in and have admin permissions for this resource.', 401 );
    }

	public function register_endpoints(){
		register_rest_route( Hispagamers_Rest_Api::REST_NAMESPACE, 'settings', array( 
	    array(
	    	'methods' => array('GET', 'POST'),
	    	'callback' => array($this, 'rest_settings' ),
	    	'permissions_callback' => array($this, 'rest_admin_only_permission_callback'),
		)));
	}


	public function rest_settings( WP_REST_Request $request ){
		$should_update = 'POST' === $request->get_method();
		$new_settings = $should_update ? $this->get_request_data( $request ) : null;
		$updated_settings = $this->get_or_update_settings($new_settings);
		return $this->rest_get_response($updated_settings);
	}

	private function get_request_data( WP_REST_Request $request){
		$content_type = $request->get_content_type();
		switch( $content_type['value']){
			case 'application/json':
				return $request->get_json_params();
			default:
				return $request->get_body_params();
		}
	}

	public function hg_get_settings_schema(){

		return array(
			'$schema' => 'http://json-schema.org/draft-04/schema#',
			'title' => 'settings',
			'type' => 'object',
			'properties' => array(
				'hg_twitter_consumerKey' => array(
					'description' => 'Twitter Consumer Key',
					'type' => 'string',
					'readonly' => false,
				),
				'hg_twitter_consumerKeySecret' => array(
					'description' => 'Twitter Consumer Key Secret',
					'type' => 'string',
					'readonly' => false
				),
				'hg_twitter_accessToken' => array(
					'description' => 'Twitter Access Token',
					'type' => 'string',
					'readonly' => false
				),
				'hg_twitter_accessTokenSecret' => array(
					'description' => 'Twitter Access Token Secret',
					'type' => 'string',
					'readonly' => false
				),
				'hg_twitch_client_id' => array(
					'description' => 'Twitch Client ID',
					'type' => 'string',
					'readonly' => false
				)
			)
		);
	}

	private function get_or_update_settings( $new_settings = null ){
		$settings = array();
		$schema = $this->hg_get_settings_schema();
		$should_update = is_array($new_settings);

		foreach($schema['properties'] as $key => $schema_value ){
			$should_update_param = $should_update && isset($new_settings[$key]) && false === $schema_value['readonly'];
			if($should_update_param ){
				update_option($key, $new_settings[$key]);
			}

			$settings[$key] = get_option($key, null);

			if(null !== $settings[$key]){
				$settings[$key] = esc_attr( $settings[$key] );
			}
		}

		return $settings;
	} 

	 /**
     * Utility function to format REST API responses.
     *
     * @since    3.0
     * @access   private
     * @param    array $data         The request data to be returned.
     * @return   WP_REST_Response    The API response object.
     */
    private function rest_get_response( array $data ) {
        return new WP_REST_Response( array(
            'code' => 'OK',
            'message' => 'Request completed successfully',
            'data' => $data,
        ), 200 );
    }

    /**
     * Utility function to format REST API errors, and to optionally log them.
     *
     * @since    3.0
     * @access   private
     * @param    string $message        The error message to be returned.
     * @param    int    $status_code    The http status code of the error.
     * @return   WP_Error               The API error object.
     */
    private function rest_get_error( $message, $status_code = 500 ) {
        return new WP_Error(
            $status_code,
            $message,
            array(
                'status' => $status_code,
            )
        );
    }


	

	
}