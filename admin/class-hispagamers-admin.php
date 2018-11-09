<?php 

class Hispagamers_admin{
	private $hispagamers;

	private $version;

	public function __construct($hispagamers, $version){
		$this->hispagamers = $Hispagamers;
		$this->version = $version;

	}


	public function enqueue_styles(){
		wp_enqueue_style( 
			$this->hispagamers,
		    HGPLUGIN_ASSETS .'/css/admin.css',
		    array( '' ), 
		    $this->version, 
		    'all' 
		);
		
	}


	public function enqueue_scripts(){
		if(!isset( $_GET['page']) || 'hispagamers' != $_GET['page']){
			return;
		}

		if(!function_exists('get_plugins')){
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		global $wp_version;

		$admin_js_vars = array(
			'rest' => array(
				'base' => esc_url_raw( rest_url( '/' )),
				'hgBase' => 'hg/v1/',
				'nonce' => wp_create_nonce('wp_rest'),
			),
			'adminUrls' => array(
				'hg' => get_admin_url(null,'admin.php?page=hispagamers'),
			),
			'permissions' => array(
				'canManageSettings' => current_user_can('manage_options'),
			),
			'site' => array(
				'name' => $this->get_site_name(),
				'pluginVersion' => $this->version,
				'allPlugins' => get_plugins(),
				'phpVersion' => phpversion(),
				'wordpressVersion' => $wp_version,
			),
		);

		// wp_enqueue_script(
		// 	$this->hispagamers .'_vendor',
		// 	plugin_dir_url( HGPLUGIN_FILE ) .'assets/js/vendor.js',
		// 	array(),
		// 	$this->version,
		// 	true
		// );


		wp_enqueue_script(
			$this->hispagamers .'_admin',
			plugin_dir_url( HGPLUGIN_FILE) .'assets/js/admin.js',
			array(),
			$this->version,
			true
		);

		wp_localize_script( $this->hispagamers .'_admin', 'HG_WP', $admin_js_vars );

	}


	public function hg_filter_rest_url( $rest_url ) {
        $rest_url_parts = parse_url( $rest_url );
        $rest_host = $rest_url_parts['host'];
        if ( array_key_exists( 'port', $rest_url_parts ) ) {
            $rest_host .= ':' . $rest_url_parts['port'];
        }

        $current_host = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $rest_host;

        if ( $rest_host !== $current_host ) {
            $rest_url = preg_replace( '/' . $rest_host . '/', $current_host, $rest_url, 1 );
        }

        return $rest_url;
    }



	public function hg_construct_admin_menu(){

		add_menu_page(
			'Hispagamers', 
			'Hispagamers', 
			'manage_options', 
			'hispagamers', 
			array($this, 'hg_render_admin_index'), 
			'dashicons-chart-pie', 
			24 
		);
		
	}

	public function hg_construct_admin_bar( $wp_admin_bar ){

		$hg_node_args = array(
			'id' => 'hispagamers',
			'title' => '<span class="ab-icon"></span>HispaGamers',
			'href' => admin_url('admin.php?page=hispagamers#/'),
			'meta' => array(
				'class' => 'hg-menu-bar',
			),
		);

		$hg_settings_node_args = array(
			'parent' => 'hispagamers',
			'id' => 'hg_settings',
			'title' => 'Configuracion',
			'href' => admin_url('admin.php?page=hispagamers#/settings')
		);

		$wp_admin_bar->add_node($hg_node_args);
		$wp_admin_bar->add_node($hg_settings_node_args);


	}

	public function hg_render_admin_index(){
		echo '<div class="wrap"><div id="vue-admin-app"></div></div>';
	}

	private function get_site_name() {
        return esc_html( get_bloginfo( 'name' ) );
    }


}