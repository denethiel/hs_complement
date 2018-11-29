<?php 


class Hispagamers_Rest_Api {
	const REST_NAMESPACE = 'hs/v1';

	const TWICH_API_BASE = 'https://api.twitch.tv/helix/';

	const TWITTER_API_BASE = 'https://api.twitter.com/1.1/';

	private $api_service;

	protected $version;

	private $cb;

	private $twitch_api;

	private $_width;

	private $_height;

	protected $loader;

	/* Este es el constructor de la clase */
	
	public function __construct( $version){
		//$this->api_service = $api_service;
		$this->version = $version;
		require_once plugin_dir_path(dirname(__FILE__)) . 'rest-api/codebird.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'rest-api/class_twitch_wp.php';
		$this->flush_apis();
		$this->_width = '1200';
		$this->_height =  '675';
	}

	public function add_5_minutes($scheludes){
		$scheludes['5_minutes'] = array(
			'interval' => 300,
			'display' => __('Cada 5 Minutos')
		);
		return $scheludes;
	}


	/**
	 *
	 * Inicializa las apis con sus respectivas keys
	 *
	 */
	
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

	public function run_bot(){
		if(! wp_next_scheduled( 'hispagamers_bot')) {
			wp_schedule_event(time(), '5_minutes','hispagamers_bot');
		}
	}

	public function stop_bot(){
		wp_clear_scheduled_hook('hispagamers_bot');
	}

	public function rest_admin_only_permission_callback( WP_REST_Request $request ) {
        // Regular cookie-based authentication.
        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        return $this->rest_get_error( 'You must be logged in and have admin permissions for this resource.', 401 );
    }

    /* REST API ENDPOINTS */
    
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

		register_rest_route(Hispagamers_Rest_Api::REST_NAMESPACE, 'post-update', array(
			array('methods' => array('POST'),
				'callback' => array($this, 'post_stream'),
				'permissions_callback' => array($this, 'rest_admin_only_permission_callback'),
		)));

		register_rest_route(Hispagamers_Rest_Api::REST_NAMESPACE,'manage-bot', array(
			array('methods' => array('POST','GET'),
				'callback' => array($this,'manage_bot'),
				'permissions_callback' => array($this, 'rest_admin_only_permission_callback'),
			)));

		register_rest_route(Hispagamers_Rest_Api::REST_NAMESPACE,'stop', array(
			array('methods' => array('POST'),
				'callback' => array($this,'stop_bot'),
				'permissions_callback' => array($this, 'rest_admin_only_permission_callback'),
			)));
	}

	public function manage_bot(WP_REST_Request $request){
		$method = $request->get_method();
		switch ($method) {
			case 'GET':
				# code...
				$status = array();
				if(! wp_next_scheduled( 'hispagamers_bot')) {
					$status['running'] = false;
				}else{
					$status['running'] = true;
				}
				return $this->rest_get_response($status);
				break;
			case 'POST':
				$params =  $this->get_request_data( $request );
				if($params['action'] === 'run'){
					$this->run_bot();
				}else if($params['action'] === 'stop'){
					$this->stop_bot();
				}
				break;
			default:
				# code...
				break;
		}
	}



	public function post_stream($twitch_id , $wp_id){
		//$wp_id = 33;
		//$twitch_id = '4536816';
		$params = array('user_id' => $twitch_id);
		$response = $this->twitch_api->api_get('streams', $params);
		$stream = $response->data[0];
		$this->_dump($stream);
		$image_raw_url = $stream->thumbnail_url;
		$match =  array('{width}','{height}');
		$replace = array($this->_width, $this->_height);
		$image_url = str_replace($match, $replace, $image_raw_url);
		$twitch_user = get_post_meta($wp_id, 'hg_streamer_twitch_user', true);
		$twitch_url = 'https://www.twitch.tv/' . $twitch_user;
		$img_reply = $this->cb->media_upload(array(
			'media' => $image_url
		));
		$reply = $this->cb->statuses_update([
			'status' => '#HispaStreamers' .' '. $stream->title . ' ' . $twitch_url ,
			'media_ids' => $img_reply->media_id_string
		]);
		date_default_timezone_set('America/Mexico_City'); 
		update_post_meta($wp_id, 'twitter_last_updated', new DateTime('now'));
		//$this->_dump($reply);

		//p_die();
	}


	public function bot(){
		$streamers = $this->_get_streamers();
		foreach ($streamers as $streamer) {
			if($streamer->live){
				//time comprobation
				date_default_timezone_set('America/Mexico_City'); 
				$now = new DateTime('now');
				if($streamer->last_updated === ''){
					$this->post_stream($streamer->twitch_id, $streamer->wp_id);
				}else{
					$diff = $streamer->last_updated->diff($now);
					$seconds = ( ($diff->days * 24 ) * 60 ) + ( $diff->i * 60 ) + $diff->s;
					if($seconds >= 900){
						$this->post_stream($streamer->twitch_id, $streamer->wp_id);
					}
				}
				
				
			}
		}
	}

	public function get_current(){
		// Get All Stream Data from Wordpress
		$streamers = $this->_get_streamers();
		usort ($streamers, function ($left, $right) {
		    return ($right->live === true);
		});
		return $this->rest_get_response($streamers);
	}

	private function _get_streamers(){
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
				$streamer_obj = (object)[
					'name' => get_the_title(),
					'wp_id' => get_the_ID(),
					'avatar' => get_post_meta(get_the_ID(), 'profile_image_url', true),
					'live' => false,
					'twitch_id' => get_post_meta(get_the_ID(), 'twitch_id', true),
					'twitch_user' => get_post_meta(get_the_ID(), 
					'hg_streamer_twitch_user', true),
					'last_updated' => get_post_meta(get_the_ID(), 
					'twitter_last_updated', true)];
				$streamers_ids[] = get_post_meta(get_the_ID(), 'twitch_id', true);
				$streamers[] =	$streamer_obj;
			}
		}else{
			//No post found
		}
		
		$params = array();
		$params['user_id'] = $streamers_ids;
		
		$response = $this->twitch_api->api_get('streams', $params);
		
		foreach ($response->data as $live) {
			foreach($streamers as $streamer){
				if($streamer->twitch_id === $live->user_id){
					$streamer->live = true;
					$streamer->thumbnail_url = $live->thumbnail_url;
					$streamer->title = $live->title;
				}
			}
		}
		
		wp_reset_postdata();
		return $streamers;
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

    private function _dump($var,$title=''){
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
            var_dump($var); 
    echo "</pre>";
    }


	

	
}