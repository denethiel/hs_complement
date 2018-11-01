<?php


// The core of the plugin

class HispaGamers{

	private $api_service;


	protected $loader;


	protected $hispagamers;


	protected $version;


	protected $shortname;


	public function __construct( $version ) {

		$this->hispagamers = 'hispagamers';
		$this->version = $version;
		$this->shortname = 'hispagamers';

		$this->load_dependencies();
	}

	private function load_dependencies()
	{
		require_once plugin_dir_path(dirname( __FILE__ )) . 'includes/class-hispagamers-loader.php';

		
	}
}