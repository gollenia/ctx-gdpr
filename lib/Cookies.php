<?php
/**
 * Managing cookies for privacy
 * 
 * @since 1.0.0
 */

namespace Contexis\Cookies;

class Cookies {

	public static $cookie_ok = 'ctx_cookie_ok';
	public static $cookie_all = 'ctx_cookie_all';

	public static function init() {
        $instance = new self;
		add_action('rest_api_init',array($instance,'register_rest'),10,1);
		add_action('admin_init',[$instance, 'add_settings']);
		add_action( 'rest_api_init',[$instance, 'add_settings'] );
		add_action('admin_menu', [ $instance, 'add_settings_menu' ], 9);    
		//	add_action('wp_footer', [$instance, 'add_cookie_window']);
		
		if(!self::get_consent_all()) {
			add_filter('render_block',[$instance, 'remove_external_blocks'], 10, 2);
		} 
	}

	public function register_rest() {
		register_rest_route( 'ctx-gdpr/v2', 'consent', [
			'methods' => \WP_REST_Server::CREATABLE, 
			'callback' => [$this, 'get_rest_data'], 
			'permission_callback' => '__return_true'
		], true );
	}

	public function get_rest_data(\WP_REST_Request $request) {
		$data = $request->get_params();
		
		setcookie(self::$cookie_ok, "true", time()+31556926, '/', $_SERVER['HTTP_HOST']);

		$result = [
			"success" => false
		];

		if(!isset($data['all'])) return;

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

	/**
     * Undocumented function
     *
     * @return void
     */
    public function add_settings_menu(){
        add_options_page( 
			__('GDPR', 'ctx-theme'),	// Name
			__('GDPR', 'ctx-theme'), 								// Title
			'manage_options', 							// Access Level
			'ctx-cookies', 					// Menu slug
			[$this, 'display_admin_settings']			// Callback
		);
        
	}

	public function add_settings() {


		register_setting(
			'general',
			'privacy_forbidden_blocks',
			array(
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
			)
	
		);

		register_setting(
			'general',
			'privacy_block_embedded',
			array(
				'type'              => 'boolean',
				'show_in_rest'      => true,
				'default'           => true,
				'sanitize_callback' => 'sanitize_text_field',
			)
	
		);

	}

	/**
     * Show Admin color settings page
     *
     * @return void
     */
	public function display_admin_settings() {
		?>
		<div class="wrap">
		        <div id="icon-themes" class="icon32"></div>  
		        <h2><?php echo __("", 'ctx-products') ?></h2>  
				<?php settings_errors(); ?>  
		        <form method="POST" action="options.php">  
		            <?php 
		                settings_fields( 'ctx-cookies' );
						$this->print_section();
		                do_settings_sections( 'ctx-cookies' ); 
                        submit_button();
		            ?>             
		        </form> 
		</div>
		<?php
	}

	public function print_settings() {
		
		
				echo "<fieldset>";
				echo "<p><label for='privacy_window_text'>" . __("You can use HTML Markup here for links, bold and italic type, line breaks and paragraphs. Leave this field empty to disable the privacy window and cookie consent entirely.", "ctx-theme") . "</label></p>";
				echo "<p><textarea rows='10' cols='50' name='privacy_window_text' id='privacy_window_text' class='large-text code'>" . get_option( 'privacy_window_text' ) . "</textarea></p>";
				echo "</fieldset>";
			
	
				echo "<input type='text' placeholder='" . __("Save settings", "ctx-theme") . "' class='regular-text' name='privacy_window_button_ok' value='" . get_option( 'privacy_window_button_ok' ) . "' />";
				
				echo "<input type='text' placeholder='" . __("Only neccessary cookies", "ctx-theme") . "' class='regular-text' name='privacy_cookies_neccessary' value='" . get_option( 'privacy_cookies_neccessary' ) . "' />";
				
				echo "<input type='text' placeholder='" . __("Third party cookies", "ctx-theme") . "' class='regular-text' name='privacy_cookies_all' value='" . get_option( 'privacy_cookies_all' ) . "' />";
	
				echo "<fieldset>";
				echo "<p><label for='privacy_cookies_explanation'>" . __("Give your visitors a short explanation of what kind of third-party-cookies they're about to accept and why you are using them", "ctx-theme") . "</label></p>";
				echo "<p><textarea rows='10' cols='50' id='privacy_cookies_explanation' name='privacy_cookies_explanation' class='large-text code' >" . get_option( 'privacy_cookies_explanation' ) . "</textarea></p>";
				echo "</fieldset>";
		
				echo "<input type='text' placeholder='" . __("Accept all", "ctx-theme") . "' class='regular-text' name='privacy_window_button_all' value='" . get_option( 'privacy_window_button_all' ) . "' />";
			
				echo "<input type='text' class='regular-text' name='privacy_window_caption' value='" . get_option( 'privacy_window_caption' ) . "' />";
			
				echo "<fieldset>";
				echo "<p><label for='privacy_forbidden_blocks'>" . __("Blocks to exclude when the user accepts only neccessary cookies. Use parts of block names or full block names. One entry per line. All embed blocks (like YouTube or Spotify) are removed automatically.", "ctx-theme") . "</label></p>";
				echo "<p><textarea rows='10' cols='50' id='privacy_forbidden_blocks' name='privacy_forbidden_blocks' class='large-text code'>" . get_option( 'privacy_forbidden_blocks' ) . "</textarea></p>";
				echo "</fieldset>";
			
		
		
	}

	public function print_section() {
		echo "<p>" . __("Here you can adjust settings concerning the European data privacy protection, also known as DSGVO or GDPR", "ctx-theme") . "</p>";
	}

	/**
	 * Check if user acceptend neccessary cookies
	 *
	 * @return bool
	 */
	public static function get_consent() {
		if(get_option( 'wp_page_for_privacy_policy' ) == get_the_ID()) return true;
		return isset($_COOKIE[self::$cookie_ok]);
	}

	/**
	 * Check if usre accepted all cookies
	 *
	 * @return bool
	 */
	public static function get_consent_all() {
		if(!isset($_COOKIE[self::$cookie_all])) return false;
		return $_COOKIE[self::$cookie_all];
	}

	/**
	 * Callback function for render_block to remove non-DSGVO-conform blocks
	 *
	 * @param string $block_content
	 * @param array $block
	 * @return string
	 */
	public function remove_external_blocks($block_content, $block ) {
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

	public function add_cookie_window() {
		?>
		<div class="ctx-d-modal <?php echo $this->get_consent() ? "" : "open" ?>" id="consentDialog">
		<Dialog class="ctx-d-dialog">
			<div class="header">
				<div class="title">
					<h2><?php echo get_option('privacy_window_caption') ?: __("Privacy consent", "ctx-theme") ?></h2>
				</div>
			</div>
			<div class="modal__content">
			<?php echo get_option('privacy_window_text'); ?>
			<form class="form">
			<div class="fieldset">
				<div class="checkbox">
					<label class="text-gray"><input type="checkbox" disabled checked><?php echo get_option('privacy_cookies_neccessary') ?: __("Only neccessary cookies", "ctx-theme") ?></label>
				</div>
				<div class="checkbox">
				<label>
					<input id="allCookiesCheck" type="checkbox" <?php echo $this->get_consent_all() ? "checked" : "" ?>>
					<?php echo get_option('privacy_cookies_all') ?: __("Third party cookies", "ctx-theme") ?>
				</label>
				<p>
					<?php get_option('privacy_cookies_explanation') ?>
				</p>
				</div>
			</div>
			</form>
			</div>
			
			<div class="modal__footer modal__footer--seperator">
				<div class="button-group button-group--right">
				
				<button id="consentPrivacy" class="button button--primary button--outline ctx-gdpr-consent-button"><?php echo get_option('privacy_window_button_ok') ?: __("Save settings", "ctx-theme") ?></button>
				<button id="consentAll" class="button button--primary button--outline ctx-gdpr-consent-button"><?php echo get_option('privacy_window_button_all') ?: __("Accept all", "ctx-theme") ?></button>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
}


Cookies::init();