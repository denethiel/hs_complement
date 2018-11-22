<?php 


class Hispagamers_Rest_Api {
	const REST_NAMESPACE = 'hs/v1';

	const TWICH_API_BASE = 'https://api.twitch.tv/helix/';

	const TWITTER_API_BASE = 'https://api.twitter.com/1.1/';

	private $api_service;

	protected $version;

	private $cb;

	private $twitch_api;

	public function __construct( $version){
		//$this->api_service = $api_service;
		$this->version = $version;
		require_once plugin_dir_path(dirname(__FILE__)) . 'rest-api/codebird.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'rest-api/class_twitch_wp.php';
		$this->flush_apis();
	}

	public function flush_apis()
	{
		$consumer_key = get_option('hg_twitter_consumerKey', null);
		$consumer_secret = get_option('hg_twitter_consumerKeySecret', null);
		$access_token = get_option('hg_twitter_accessToken', null);
		$access_token_secret = get_option('hg_twitter_accessTokenSecret', null);
		$client_id = get_option('hg_twitch_client_id', null);
		if($consumer_key !== null && $consumer_secret !== null && $access_token !== null && $access_token_secret !== null )
		{
			\Codebird\Codebird::setConsumerKey($consumer_key, $consumer_secret);
			$this->cb = \Codebird\Codebird::getInstance();
			$this->cb->setToken($access_token, $access_token_secret);
		}

		if($client_id !== null){
			\Twitch\Twitch_Api::setClientId($client_id);
			$this->twitch_api = \Twitch\Twitch_Api::getInstance();
		}



		// $reply = $this->cb->statuses_update(array(
		// 	'status' => 'This is a test'));
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

		register_rest_route( Hispagamers_Rest_Api::REST_NAMESPACE, 'current', array(
			array(
				'methods' => array('GET'),
				'callback' => array($this, 'get_current'),
				'permissions_callback' => array($this, 'rest_admin_only_permission_callback'),
			)));
	}

	public function get_current(){
		// Get All Stream Data from Wordpress
		$args = array(
			'post_type' => 'hg_streamer',
			'post_status' => 'publish',
			'nopaging' => true,
			'order' => 'ASC',
			'orderby' => 'title'
		);

		$streamers = array();
		$streamers_ids = array();

		$raw_streamers = new WP_Query($args);

		if($raw_streamers->have_posts()){
			while($raw_streamers->have_posts()){
				$raw_streamers->the_post();
				//information
				$streamer_id = get_the_ID();
				$streamers_ids[] = get_post_meta($streamer_id,'twitch_id', true);
				the_title( '<h3>', '</h3>' );
			}
		}else{
			//No post found
		}
		
		$params = array();
		$params['user_id'] = $streamers_ids;
		var_dump($params);
		$response = $this->twitch_api->api_get('streams', $params);
		echo "  
    <style>
        /* Styling pre tag */
        pre {
            padding:10px 20px;
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;
        }

        /* ===========================
        == To use with XDEBUG 
        =========================== */
        /* Source file */
        pre small:nth-child(1) {
            font-weight: bold;
            font-size: 14px;
            color: #CC0000;
        }
        pre small:nth-child(1)::after {
            content: '';
            position: relative;
            width: 100%;
            height: 20px;
            left: 0;
            display: block;
            clear: both;
        }

        /* Separator */
        pre i::after{
            content: '';
            position: relative;
            width: 100%;
            height: 15px;
            left: 0;
            display: block;
            clear: both;
            border-bottom: 1px solid grey;
        }  
    </style>
    ";

    //=== Content            
    echo "<pre style='background:$background; color:$color; padding:10px 20px; border:2px inset $color'>";
    echo    "<h2>$title</h2>";
            var_dump($response); 
    echo "</pre>";

		wp_reset_postdata();
		wp_die();
	}




	public function save_twitch_user_id($post_id){

		$post_type = get_post_type($post_id);

		if('hg_streamer' != $post_type) return;

		$twitch_user = (isset($_POST['hg_streamer_twitch_user'])?$_POST['hg_streamer_twitch_user']:'');

		if($twitch_user === '') return;

		$params = array('login' => $twitch_user);
		$response = $this->twitch_api->api_get('users',$params);
		$twitch_id = $response->data[0]->id;
		$profile_img = $response->data[0]->profile_image_url;
		update_post_meta($post_id, 'profile_image_url',$profile_img);
		update_post_meta($post_id, 'twitch_id', $twitch_id);


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