<?php 

class Hispagamers_public{

	private $hispagamers;

	private $version;

	public function __construct($hispagamers, $version){
		$this->hispagamers = $hispagamers;
		$this->version = $version;
	}

	public function enqueue_script ($hook_page){

		$script_handle = $this->hispagamers . '_main';
		wp_enqueue_script( $script_handle,
						   plugin_dir_url(__FILE__) . 'js/build.js',
						   array(), 
						   $this->version,
						   true);
	}

	public function hispagamers_integrateWithVC(){

		/* Streams VC 
		-------------------------------------------*/
		vc_map(array(
			'name' => esc_html__( 'Streams', 'hg' ),
			'base' => 'hg_streams',
			'icon' => 'icon-wpb-layer-shape-text',
			'wrapper_class' => 'clearfix',
			'category' => esc_html__('Content', 'hg'),
			'description' => esc_html__('Add Streamers to your page.', 'hg'),
			'html_template'  => dirname( __FILE__ ) . '/vc_templates/hg_streams.php',
			'front_enqueue_js' => preg_replace( '/\s/', '%20', plugins_url( 'public/js/build.js', __FILE__ ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Titulo(opcional)','hg'),
					'param_name' => 'hg_stream_title',
					'holder' => 'div',
					'value' => '',
					'description' => esc_html__('Añade un titulo a el bloque de streamers','hg')
					),
			)
		));


	}



}