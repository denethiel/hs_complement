<?php 
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://hispagamers.com
 * @since             0.0
 * @package           HispaGamers
 *
 * @wordpress-plugin
 * Plugin Name:       HispaGamers Theme Complement
 * Plugin URI:        http://hispagamers.com
 * Description:       Plugin Complemento para el sitio web
 * Version:           0.0.1
 * Author:            Jose David Pacheco Valedo
 * Author URI:        https://denethiel.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hg
 * Domain Path:       /languages
 */
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

function complement_vc_map(){
	if ( class_exists( "WPBakeryShortCode" ) ) {
			class WPBakeryShortCode_HG_streams extends WPBakeryShortCode {}
	}
}

add_action('vc_after_init', 'complement_vc_map');


$consumer_key = 'tm7nueKh1U1QShxP0Gb8A';
$consumer_secret = 'Ka4s3zjrE7K5JaWU0pvdzu16WC34r6jx01TYzPgSOSU';
$access_token = '545681441-CQscmMOUJTEXEpf5cpTgj7zwsL7JDvgNk4YHaEJT';
$access_token_secret = 'hhTeh3woVW6G956gl3Z4fNPzAdMmOkP1DekL5IzK63EBG';

register_activation_hook( __FILE__, 'activate_hs' );

register_deactivation_hook( __FILE__, 'deactivate_hs' );


require plugin_dir_path( __FILE__ ) . 'includes/class-hispagamers.php';

require plugin_dir_path(__FILE__) . 'includes/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;






function run_hs() {
	global $HS_VERSION;
	$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);


	$content = $connection->get("account/verify_credentials");
	$plugin = new HispaGamers($HS_VERSION, $connection);
	$plugin->run();
}

run_hs();

