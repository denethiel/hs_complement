<?php


// The core of the plugin

class HispaGamers{

	private $twitter_service;

	private $twich_service;


	protected $loader;


	protected $hispagamers;


	protected $version;


	protected $shortname;


	public function __construct( $version, $twitteroauth ) {

		$this->hispagamers = 'hispagamers';
		$this->version = $version;
		$this->shortname = 'hispagamers';

		

		$this->twitter_service = $twitteroauth;

		$this->load_dependencies();

		$this->define_custom_types_hooks();
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-hispagamers-loader.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-hispagamers-custom-types.php';


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