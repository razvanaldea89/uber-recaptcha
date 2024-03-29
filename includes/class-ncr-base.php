<?php


/**
 * The base-class of the plugin.
 *
 * Defines the plugin, loads the text domain, holds the PHP function handling the reCaptcha validation
 * enqueues the front-end specific stylesheet and JavaScript.
 *
 * @package    Uber_Recaptcha
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */
class NCR_base_class {

	/**
	 * The site key to be used with the reCaptcha API.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $recaptcha_public_key The site key to be used with the reCaptcha API.
	 */
	protected $recaptcha_public_key;


	/**
	 * The secret key to be used with the reCaptcha API.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $recaptcha_private_key The secret key to be used with the reCaptcha API.
	 */
	protected $recaptcha_private_key;


	/**
	 * The theme of the reCaptcha API that's going to be used when generating the mark-up for the reCaptcha HTML tag.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $data_theme The theme of the reCaptcha API that's going to be used when generating the mark-up
	 *                              for the reCaptcha HTML tag..
	 */
	protected $data_theme;


	/**
	 * Holds the plugin settings; extracted from the database.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_settings Holds the plugin settings; extracted from the database.
	 */
	protected $plugin_settings;

	/**
	 * Holds the default settings for the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $default_settings Default plugin settings
	 */
	protected static $default_settings;

	/**
	 * Sets the type of reCaptcha you want - image / audio.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $data_type reCaptcha data type (audio/image)
	 */
	protected $data_type;

	/**
	 * reCaptcha key type ( invisible/normal ); normal by default
	 *
	 * @since    1.1.4
	 * @access   protected
	 * @var      string $key_type reCaptcha language
	 */
	protected $key_type = 'normal';

	/**
	 * reCaptcha language
	 *
	 * @since    1.1.4
	 * @access   protected
	 * @var      string $captcha_language reCaptcha language
	 */
	protected $captcha_language = '';

	/**
	 * reCaptcha disable login button
	 *
	 * @since    1.1.4
	 * @access   protected
	 * @var      string $disable_submit_button reCaptcha disable login button
	 */
	protected $disable_submit_button = 'no';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param array  $plugin_settings       Holds the plugin settings; extracted from the database..
	 * @param string $recaptcha_public_key  The site key to be used with the reCaptcha API.
	 * @param string $recaptcha_private_key The secret key to be used with the reCaptcha API.
	 * @param string $data_theme            The theme of the reCaptcha API (dark / light)
	 * @param string $data_type             reCaptcha data type (audio/image)
	 *
	 * @since      1.0.0
	 *
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'uncr_load_plugin_textdomain' ) );

		// saved plugin settings
		$this->plugin_settings = get_option( 'uncr_settings' );

		if ( ! isset( $this->plugin_settings['captcha_key_type'] ) ) {
			$this->plugin_settings['captcha_key_type'] = 'normal';
		}
		if ( ! isset( $this->plugin_settings['disable_submit_button'] ) ) {
			$this->plugin_settings['disable_submit_button'] = 'no';
		}

		// default plugin settings
		self::$default_settings = array(
			'public_key_text'         => '',
			'private_key_text'        => '',
			'captcha_key_type'        => 'normal',
			'captcha_theme_radio'     => 'light',
			'captcha_type_radio'      => 'image',
			'captcha_language_select' => '',
			'uncr_login_form'         => '',
			'uncr_register_form'      => '',
			'uncr_comment_form'       => '',
			'uncr_lost_pwd'           => '',
			'show_logged_users'       => array(),
			'disable_submit_button'   => 'no'
		);

		// reCaptcha secret key
		$this->recaptcha_private_key = ! empty( $this->plugin_settings['private_key_text'] ) ? $this->plugin_settings['private_key_text'] : '';

		// reCaptcha site key
		$this->recaptcha_public_key = ! empty( $this->plugin_settings['public_key_text'] ) ? $this->plugin_settings['public_key_text'] : '';

		// reCaptcha key type ( invisible/normal ); normal by default
		$this->key_type = ! empty( $this->plugin_settings['captcha_key_type'] ) ? $this->plugin_settings['captcha_key_type'] : 'normal';

		// reCaptcha theme (light/dark); light theme is set by default
		$this->data_theme = ! empty( $this->plugin_settings['captcha_theme_radio'] ) ? $this->plugin_settings['captcha_theme_radio'] : '';

		// reCaptcha data type (audio/image); image is set by default.
		$this->data_type = ! empty( $this->plugin_settings['captcha_type_radio'] ) ? $this->plugin_settings['captcha_type_radio'] : '';

		// reCaptcha language
		$this->captcha_language = ! empty( $this->plugin_settings['captcha_language_select'] ) ? $this->plugin_settings['captcha_language_select'] : '';

		// reCaptcha disable login button
		$this->disable_submit_button = ! empty( $this->plugin_settings['disable_submit_button'] ) ? $this->plugin_settings['disable_submit_button'] : 'no';

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function uncr_load_plugin_textdomain() {

		load_plugin_textdomain( 'uncr_translate', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * If a script handle with the name "recaptcha" already exists, return a WP error
	 *
	 * @return  WP_Error
	 * @since   1.0.0
	 */
	public function uncr_header_script() {

		if ( ! wp_script_is( 'recaptcha', 'register' ) ) { // if a script with the same handle hasn't been already registered, register ours

			if ( ! empty( $this->captcha_language ) ) {
				wp_register_script( 'recaptchaAPI', '//www.google.com/recaptcha/api.js?onload=renderUNCRReCaptcha&render=explicit&hl=' . $this->captcha_language, null, '2.1', false );
				wp_register_script( 'recaptchaGenerate', plugins_url( 'assets/js/recaptcha.js', dirname( __FILE__ ) ), array(
					'recaptchaAPI',
					'jquery',
				),                  '1.0', false );
			} else {
				wp_register_script( 'recaptchaAPI', '//www.google.com/recaptcha/api.js?onload=renderUNCRReCaptcha&render=explicit', null, '2.1', false );
				wp_register_script( 'recaptchaGenerate', plugins_url( 'assets/js/recaptcha.js', dirname( __FILE__ ) ), array(
					'recaptchaAPI',
					'jquery',
				),                  '1.0', false );
			}
			wp_enqueue_script( 'recaptchaAPI' );
			wp_enqueue_script( 'recaptchaGenerate' );

			$recapcha_settings = array(
				'site_key'      => $this->recaptcha_public_key,
				'key_type'      => $this->key_type,
				'theme'         => $this->data_theme,
				'type'          => $this->data_type,
				'submit_button' => $this->disable_submit_button,
			);
			wp_localize_script( 'recaptchaGenerate', 'UNCR', $recapcha_settings );

		} else {
			return new WP_Error( 'script_handle_exists', __( 'A script with the same has already been registered. Plugin conflict', 'uncr_translate' ) );
		}


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function uncr_wp_css() {
		wp_register_style( 'captcha-style', plugins_url( 'assets/css/style.css', dirname( __FILE__ ) ) );
		wp_enqueue_style( 'captcha-style' );
	}

	/**
	 * Function that handles the display of the reCaptcha HTML mark-up.
	 *
	 * @since    1.0.0
	 */
	public function uncr_display_captcha() {
		echo '<div class="uncr-g-recaptcha"></div>';
	}

	/**
	 * The function that validates the captcha answer against Googles' servers.
	 *
	 * @since   1.0.0
	 */
	public function uncr_validate_captcha() {
		$challenge = ! empty( $_POST['g-recaptcha-response'] ) ? esc_attr( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : '';

		// get user IP address
		$remote_ip = $_SERVER["REMOTE_ADDR"];

		// format the post_body to make a $_POST request with
		$post_body = array(
			'secret'   => $this->recaptcha_private_key,
			'remoteip' => $remote_ip,
			'response' => $challenge,
		);

		$args = array( 'body' => $post_body );

		// make a request to the Google Recaptcha API
		$request = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', $args );

		// get the request response body
		$response_body = wp_remote_retrieve_body( $request );

		return $response_body;

	}

	/**
	 * The code that is executed when the plugin is activated.
	 *
	 * @since   1.0.0
	 */
	static function uncr_plugin_install() {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		add_option( 'uncr_settings', self::$default_settings );
	}

	/**
	 * The function that is executed when the plugin is deactivated.
	 *
	 * @since   1.0.0
	 */
	static function uncr_plugin_uninstall() {


		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

	}
}