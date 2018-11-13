<?php


// The core of the plugin

class HispaGamers{


	private $twich_service;


	protected $loader;


	protected $hispagamers;


	protected $version;


	protected $shortname;


	public function __construct( $version ) {

		$this->hispagamers = 'hispagamers';
		$this->version = $version;
		$this->shortname = 'hispagamers';

		

		$this->load_dependencies();

		$this->define_custom_types_hooks();

		$this->define_public_hooks();


		$this->define_admin_hooks();

		$this->define_rest_api_hooks();

		//$statues = $this->twitter_service->post("statuses/update", ["status" => "hello world"]);

		//var_dump($statues);
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-hispagamers-loader.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hispagamers-custom-types.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-hispagamers-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-hispagamers-admin.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'rest-api/class-hispagamers-rest-api.php';


		$this->loader = new Hispagamers_Loader();

		
		
	}


	private function define_custom_types_hooks(){
		$plugin_post_type = new Hispagamers_custom_types($this->get_hispagamers_name(), $this->get_version());
		$this->loader->add_action('init',$plugin_post_type,'register_streamer_type', 0);

		$this->loader->add_action('init',$plugin_post_type,'streamers_taxonomies');

		$this->loader->add_action('add_meta_boxes', $plugin_post_type, 'streamer_meta_box');

		$this->loader->add_filter('enter_title_here',$plugin_post_type,'change_defaul_title');

		$this->loader->add_action( 'save_post', $plugin_post_type, 'streamer_meta_save', 10, 2 );
	}

	private function define_public_hooks(){
		$plugin_public = new Hispagamers_public($this->get_hispagamers_name(), $this->get_version());

		$this->loader->add_action('vc_before_init',$plugin_public, 'hispagamers_integrateWithVC');

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_script');


	}

	private function define_admin_hooks(){
		$plugin_admin = new Hispagamers_admin($this->get_hispagamers_name(), $this->get_version());

		$this->loader->add_filter('rest_url', $plugin_admin, 'hg_filter_rest_url');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu',$plugin_admin, 'hg_construct_admin_menu');
		$this->loader->add_action('admin_bar_menu', $plugin_admin, 'hg_construct_admin_bar', 999);

	}

	private function define_rest_api_hooks(){
		$plugin_rest_api = new Hispagamers_Rest_Api($this->get_version());

		$this->loader->add_action( 'rest_api_init', $plugin_rest_api, 'register_endpoints' );
	}

	public function get_hispagamers_name(){
		return $this->hispagamers;
	}

	public function get_version(){
		return $this->version;
	}

	public function run(){
		$this->loader->run();
	}
}