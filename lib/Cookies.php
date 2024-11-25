<?php

namespace Contexis\Cookies;

class Cookies {

	public static $cookie_ok = 'ctx_cookie_ok';
	public static $cookie_all = 'ctx_cookie_all';

	public static function init() : void {
        $instance = new self;
		add_action('rest_api_init',array($instance,'register_rest'),10,1);
		
		if(!self::get_consent_all()) {
			add_filter('render_block',[$instance, 'remove_external_blocks'], 10, 2);
		} 
	}

	public function register_rest() : void {
		register_rest_route( 'ctx-gdpr/v2', 'consent', [
			'methods' => \WP_REST_Server::CREATABLE, 
			'callback' => [$this, 'get_rest_data'], 
			'permission_callback' => '__return_true'
		], true );
	}

	public function get_rest_data(\WP_REST_Request $request) : array {
		$data = $request->get_params();
		
		setcookie(self::$cookie_ok, "true", time()+31556926, '/', $_SERVER['HTTP_HOST']);

		$result = [
			"success" => false
		];

		if(!isset($data['all'])) return $result;

		if($data['all'] == 1) {
			setcookie(self::$cookie_all, "true", time()+31556926, '/', $_SERVER['HTTP_HOST']);	
			$result['success'] = true;
		}

		if($data['all'] == 0) {
			setcookie(self::$cookie_all, "", -1, '/', $_SERVER['HTTP_HOST']);	
			unset($_COOKIE[self::$cookie_all]); 
			$result['success'] = true;
		}

		return $result;
		
	}

	public static function get_consent() : bool {
		if(get_option( 'wp_page_for_privacy_policy' ) == get_the_ID()) return true;
		return isset($_COOKIE[self::$cookie_ok]);
	}

	public static function get_consent_all() : bool {
		if(!isset($_COOKIE[self::$cookie_all])) return false;
		return $_COOKIE[self::$cookie_all];
	}

	public function remove_external_blocks(string $block_content, array $block ) : string {
		if(!isset($block['blockName'])) return $block_content;
		if( str_contains($block['blockName'], 'embed')) {
			$block_content = '';
		}

		$blocks_to_remove = get_option('privacy_forbidden_blocks');

		$blocks = explode( "\n", $blocks_to_remove );

		foreach ($blocks as $blockname) {
			if($blockname == '') break;
			if( str_contains($block['blockName'], $blockname)) {
				$block_content = '';
			}
		}
		
		return $block_content;
	}

}


Cookies::init();