<?php 

$HS_VERSION = '0.0.1';

if( !defined('WPINC')) {
	die;
}

function activate_hs(){
	if(version_compare(phpversion(),'5.4','<')){
		deactivate_plugins(plugin_basename( __FILE__ ));
		wp_die('Este complemento require una version de PHP superior a 5.4. El plugin no podra activarse');
	}
}

function deactivate_hs(){
	// require_once plugin_dir_path(__FILE__) . 'includes/class-hs-deactivator.php';
	// HS_Deactuvator::deactivate();
}


register_activation_hook( __FILE__, 'activate_hs' );

register_deactivation_hook( __FILE__, 'deactivate_hs' );


require plugin_dir_path( __FILE__ ) . 'includes/class-hispagamers.php';


function run_hs() {
	global $HS_VERSION;
	$plugin = new HispaGamers($HS_VERSION);
	$plugin->run();
}

run_hs();

