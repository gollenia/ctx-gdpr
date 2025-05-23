<?php
/**
 * Plugin Name:     CTX GDPR 
 * Description:     Additional Blocks for GDPR
 * Version:         1.0.3
 * Author:          Thomas Gollenia
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     ctx-gdpr
 *
 */

/**
 * New Block registration making use of the new block.json format.
 */
function ctx_gdpr_init() {

	$dir = __DIR__ . "/build/";

	if ( ! file_exists( $dir . "index.asset.php" ) ) {
		
		  return;
	}
	
	$script_asset = require( $dir . "index.asset.php" );

	if(is_admin()) {
		wp_register_script(
			"ctx-gdpr-editor",
			plugins_url( '/build/index.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
		wp_set_script_translations( "ctx-gdpr-editor", 'ctx-gdpr', plugin_dir_path( __FILE__ ) . 'languages' );
		
		wp_register_style(
			"ctx-gdpr-editor-style",
			plugins_url( 'build/index.css', __FILE__ ),
			array(),
			$script_asset['version']
		);
	}

	if(!is_admin()) {
		wp_register_style(
			"ctx-gdpr-style",
			plugins_url( 'build/style-index.css', __FILE__ ),
			array(),
			$script_asset['version']
		);
		wp_enqueue_script('ctx-gdpr-frontend', plugin_dir_url(__FILE__) . "build/gdpr.js", [], false, true);
		//wp_enqueue_style('ctx-gdpr-frontend', plugin_dir_url(__FILE__) . "build/gdpr.css", [], false);
	}


	register_block_type( __DIR__ . '/build/blocks/link' );
	
	
}

add_action( 'init', 'ctx_gdpr_init' );

function ctx_gdpr_load_textdomain() {
	load_plugin_textdomain('ctx-gdpr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'ctx_gdpr_load_textdomain' );

require_once __DIR__ . '/lib/Cookies.php';
require_once __DIR__ . '/lib/Update.php';

new \Contexis\Cookies\Update(
	__FILE__,
	'gollenia',
	'ctx-gdpr'
);
