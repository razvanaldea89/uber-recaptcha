<?php


/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    Uber_Recaptcha
 * @subpackage Uber_Recaptcha/admin/settings
 * @author     Cristian Raiber <hi@cristian.raiber.me>
 */
class NCR_options_panel extends NCR_render_engine {

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ncr_register_menu_page' ) );

		// load styles
		add_action( 'admin_enqueue_scripts', array( $this, 'back_end_styles' ) );
	}


	/**
	 * Function that handles the creation of a new menu page for the plugin
	 *
	 * @since   1.0.0
	 */
	public function ncr_register_menu_page() {
		add_menu_page(
			__( 'Uber reCaptcha', 'uncr_translate' ),       // page title
			__( 'Uber reCaptcha', 'uncr_translate' ),       // menu title
			'manage_options',                               // capability
			'uber-ncr-settings',                            // menu-slug
			array( $this, 'ncr_render_settings' ),          // callback function to render the options
			'dashicons-shield-alt'  // icon
		);
	}

	public function back_end_styles() {
		// Load scripts
		wp_register_script( 'uncr-back-end-script', UNCR__PLUGIN_URL . '/assets/js/back-end-scripts.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_script( 'uncr-back-end-script' );

		// load styles
		wp_register_style( 'uncr-back-end-style', UNCR__PLUGIN_URL . '/assets/css/back-end-styles.css', false, '1.0' );
		wp_enqueue_style( 'uncr-back-end-style' );
	}

	/**
	 * Function that holds the required back-end field mark-up.
	 *
	 * @since   1.0.0
	 *
	 * @return  array   $settings   Holds all the mark-up required for the field rendering engine to render the fields
	 */
	public function ncr_settings_fields() {

		$settings = array(

			'public-key'            => array(
				'title' => __( 'reCaptcha site key', 'uncr_translate' ),
				'type'  => 'text',
				'id'    => 'public_key_text', // id is generated using name + '_' + type
			),
			'private-key'           => array(
				'title' => __( 'reCaptcha secret key', 'uncr_translate' ),
				'type'  => 'text',
				'id'    => 'private_key_text', // id is generated using name + '_' + type
			),
			'captcha-model'         => array(
				'title'   => __( 'reCaptcha model', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_model_type',
				'options' => array( // keys in the array should always be prefixed
					'v2' => __( 'reCaptcha V2', 'uncr_translate' ),
					'v3' => __( 'reCaptcha V3', 'uncr_translate' ),
				),
			),
			'captcha-key-type'      => array(
				'title'   => __( 'Captcha key type', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_key_type',
				'options' => array( // keys in the array should always be prefixed
					'invisible' => __( 'Invisible', 'uncr_translate' ),
					'normal'    => __( 'I\'m not a robot.', 'uncr_translate' ),
				),
			),
			'captcha-theme'         => array(
				'title'   => __( 'Select reCaptcha skin', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_theme_radio',
				'options' => array(
					'dark'  => __( 'Dark Theme', 'uncr_translate' ),
					'light' => __( 'Light Theme', 'uncr_translate' ),
				),
			),
			'captcha-type'          => array(
				'title'   => __( 'Captcha type', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'captcha_type_radio',
				'options' => array( // keys in the array should always be prefixed
					'audio' => __( 'Audio Captcha', 'uncr_translate' ),
					'image' => __( 'Image Captcha', 'uncr_translate' ),
				),
			),
			'disable-submit-button' => array(
				'title'   => __( 'Disable submit button ?', 'uncr_translate' ),
				'type'    => 'radio',
				'id'      => 'disable_submit_button',
				'options' => array( // keys in the array should always be prefixed
					'yes' => __( 'Yes', 'uncr_translate' ),
					'no'  => __( 'No', 'uncr_translate' ),
				),
			),
			'captcha-language'      => array(
				'title'   => __( 'reCaptcha language', 'uncr_translate' ),
				'type'    => 'select',
				'id'      => 'captcha_language_select',
				'options' => array(
					__( 'Auto Detect', 'uncr_translate' )  => '',
					__( 'Arabic', 'uncr_translate' )       => 'ar',
					__( 'Afrikaans', 'uncr_translate' )    => 'af',
					__( 'Amharic', 'uncr_translate' )      => 'am',
					__( 'Armenian', 'uncr_translate' )     => 'hy',
					__( 'Azerbaijani', 'uncr_translate' )  => 'az',
					__( 'Basque', 'uncr_translate' )       => 'eu',
					__( 'Bengali', 'uncr_translate' )      => 'bn',
					__( 'Bulgarian', 'uncr_translate' )    => 'bg',
					__( 'Catalan', 'uncr_translate' )      => 'ca',
					__( 'Chinese (Hong Kong)', 'uncr_translate' ) => 'zh-HK',
					__( 'Chinese (Simplified)', 'uncr_translate' ) => 'zh-CN',
					__( 'Chinese (Traditional)', 'uncr_translate' ) => 'zh-TW',
					__( 'Croatian', 'uncr_translate' )     => 'hr',
					__( 'Czech', 'uncr_translate' )        => 'cs',
					__( 'Danish', 'uncr_translate' )       => 'da',
					__( 'Dutch', 'uncr_translate' )        => 'nl',
					__( 'English (UK)', 'uncr_translate' ) => 'en-GB',
					__( 'English (US)', 'uncr_translate' ) => 'en',
					__( 'Estonian', 'uncr_translate' )     => 'et',
					__( 'Filipino', 'uncr_translate' )     => 'fil',
					__( 'Finnish', 'uncr_translate' )      => 'fi',
					__( 'French', 'uncr_translate' )       => 'fr',
					__( 'French (Canadian)', 'uncr_translate' ) => 'fr-CA',
					__( 'Galician', 'uncr_translate' )     => 'gl',
					__( 'Georgian', 'uncr_translate' )     => 'ka',
					__( 'German', 'uncr_translate' )       => 'de',
					__( 'German (Austria)', 'uncr_translate' ) => 'de-AT',
					__( 'German (Switzerland)', 'uncr_translate' ) => 'de-CH',
					__( 'Greek', 'uncr_translate' )        => 'el',
					__( 'Gujarati', 'uncr_translate' )     => 'gu',
					__( 'Hebrew', 'uncr_translate' )       => 'iw',
					__( 'Hindi', 'uncr_translate' )        => 'hi',
					__( 'Hungarain', 'uncr_translate' )    => 'hu',
					__( 'Icelandic', 'uncr_translate' )    => 'is',
					__( 'Indonesian', 'uncr_translate' )   => 'id',
					__( 'Italian', 'uncr_translate' )      => 'it',
					__( 'Japanese', 'uncr_translate' )     => 'ja',
					__( 'Kannada', 'uncr_translate' )      => 'kn',
					__( 'Korean', 'uncr_translate' )       => 'ko',
					__( 'Laothian', 'uncr_translate' )     => 'lo',
					__( 'Latvian', 'uncr_translate' )      => 'lv',
					__( 'Lithuanian', 'uncr_translate' )   => 'lt',
					__( 'Malay', 'uncr_translate' )        => 'ms',
					__( 'Malayalam', 'uncr_translate' )    => 'ml',
					__( 'Marathi', 'uncr_translate' )      => 'mr',
					__( 'Mongolian', 'uncr_translate' )    => 'mn',
					__( 'Norwegian', 'uncr_translate' )    => 'no',
					__( 'Persian', 'uncr_translate' )      => 'fa',
					__( 'Polish', 'uncr_translate' )       => 'pl',
					__( 'Portuguese', 'uncr_translate' )   => 'pt',
					__( 'Portuguese (Brazil)', 'uncr_translate' ) => 'pt-BR',
					__( 'Portuguese (Portugal)', 'uncr_translate' ) => 'pt-PT',
					__( 'Romanian', 'uncr_translate' )     => 'ro',
					__( 'Russian', 'uncr_translate' )      => 'ru',
					__( 'Serbian', 'uncr_translate' )      => 'sr',
					__( 'Sinhalese', 'uncr_translate' )    => 'si',
					__( 'Slovak', 'uncr_translate' )       => 'sk',
					__( 'Slovenian', 'uncr_translate' )    => 'sl',
					__( 'Spanish', 'uncr_translate' )      => 'es',
					__( 'Spanish (Latin America)', 'uncr_translate' ) => 'es-419',
					__( 'Swahili', 'uncr_translate' )      => 'sw',
					__( 'Swedish', 'uncr_translate' )      => 'sv',
					__( 'Tamil', 'uncr_translate' )        => 'ta',
					__( 'Telugu', 'uncr_translate' )       => 'te',
					__( 'Thai', 'uncr_translate' )         => 'th',
					__( 'Turkish', 'uncr_translate' )      => 'tr',
					__( 'Ukrainian', 'uncr_translate' )    => 'uk',
					__( 'Urdu', 'uncr_translate' )         => 'ur',
					__( 'Vietnamese', 'uncr_translate' )   => 'vi',
					__( 'Zulu', 'uncr_translate' )         => 'zu',

				),
			),

			'captcha-presence'      => array(
				'title'   => __( 'Enable reCaptcha on:', 'uncr_translate' ),
				'type'    => 'checkbox',
				'id'      => 'captcha_presence_checkbox',
				'options' => array( // keys in the array should always be prefixed
					'uncr_login_form'    => __( 'Login Screen', 'uncr_translate' ),
					'uncr_register_form' => __( 'Register Screen', 'uncr_translate' ),
					'uncr_comment_form'  => __( 'Comment Form', 'uncr_translate' ),
					'uncr_lost_pwd'      => __( 'Recover Password Form', 'uncr_translate' ),
				),
			),

			'show-logged-users'     => array(
				'title'   => __( 'Enable reCaptcha for logged in users:', 'uncr_translate' ),
				'type'    => 'multicheckbox',
				'id'      => 'show_logged_users',
				'options' => $this->uncr_get_wp_roles(),
			),

		);

		return $settings;
	}

	/**
	 * Function that registers the settings sections
	 */
	public function ncr_render_settings() {

		// Check that the user is allowed to update options
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'uncr_translate' ) );
		}

		// save options
		$this->ncr_save_settings();

		register_setting( 'uncr_settings_group', 'uncr_settings', array( $this, 'uncr_validate_fields' ) );
		add_settings_section( 'uncr_settings_section', null, null, 'uncr_settings_section_call' );

		foreach ( $this->ncr_settings_fields() as $settings_id => $settings_arguments ) {

			add_settings_field(
				$settings_id,                        // unique ID for the field
				$settings_arguments['title'],        // title of the field
				array( $this, 'ncr_render_field' ),   // function callback
				'uncr_settings_section_call',        // page name, should be the same as the last argument used in add_settings_section
				'uncr_settings_section',             // same as first argument passed to add_settings_section
				$settings_arguments                  // $args, passed as array; defined in ncr_settings_field()
			);
		} ?>

		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class='wrap uncr-wrap'>

			<h1><?php esc_html_e( 'Uber Google reCaptcha plugin', 'uncr_translate' ); ?></h1>
			<p class="uncr-about-text"><?php echo esc_html__( 'Thank you for choosing Uber Google reCaptcha plugin. An easy to use security plugin that adds Googles\' reCaptcha to comment, register & lost password forms.', 'uncr_translate' ); ?>

			<div class='uncr-badge'>
				<span><?php echo esc_html__( 'Version: ', 'uncr_translate' ) . esc_html( UNCR__PLUGIN_VERSION ); ?></span></div>

			<?php settings_errors(); ?>

			<form method="post" action="">

				<?php

				$options = get_option( 'uncr_settings' );

				if ( empty( $options['public_key_text'] ) || empty( $options['private_key_text'] ) ) {
					?>
					<p class="get-recaptcha-key"><?php echo esc_html__( 'Get your reCaptcha site & secret keys by clicking the following link:  ', 'uncr_translate' ) . '<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank" title="Google reCaptcha">' . esc_html__( 'Opens in a new tab', 'uncr_translate' ) . '</a>'; ?></p>
				<?php } ?>

				<?php settings_fields( 'uncr_settings_group' );               // settings group, defined as first argument in register_setting ?>
				<?php do_settings_sections( 'uncr_settings_section_call' );   // same as last argument used in add_settings_section ?>
				<?php submit_button(); ?>

				<?php wp_nonce_field( 'uncr_settings_nonce' ); ?>
				<div class="clear"></div>
			</form>

			<div class="clear"></div>

		</div>

		<?php
	}

	/**
	 * Function that calls the rendering engine
	 *
	 * @param   array $args Each array entry defiend in the ncr_settings_fields() is passed as a parameter to this
	 *                      function
	 *
	 * @since   1.0.0
	 */

	public function ncr_render_field( $args ) {

		switch ( $args['type'] ) {

			case 'text':
				echo $this->render_text_field( $args );
				break;
			case 'radio':
				echo $this->render_radio_field( $args );
				break;
			case 'multicheckbox':
				echo $this->render_multicheckbox_field( $args );
				break;
			case 'checkbox':
				echo $this->render_checkbox_field( $args );
				break;
			case 'select':
				echo $this->render_select_field( $args );
				break;

		}
	}


	/**
	 * Function that saves the plugin options to the database
	 *
	 * @since   1.0.0
	 */
	public function ncr_save_settings() {

		if ( isset( $_POST['uncr_settings'] ) && check_admin_referer( 'uncr_settings_nonce', '_wpnonce' ) ) {

			foreach ( $_POST['uncr_settings'] as $field_id => $field ) {
				if ( is_array( $field ) ) {
					$_POST['uncr_settings'][ $field_id ] = array_map( 'sanitize_text_field', $_POST['uncr_settings'][ $field_id ] );
				} else {
					$_POST['uncr_settings'][ $field_id ] = sanitize_text_field( $_POST['uncr_settings'][ $field_id ] );
				}
			}

			update_option( 'uncr_settings', $_POST['uncr_settings'] );

		}
	}

	public function uncr_validate_fields( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) && ! is_array( $value ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			} else {

				$output[ $key ] = $value;

			} // end if
		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'uncr_plugin_validate_settings', $output, $input );
	}

	public function uncr_get_wp_roles() {
		$editable_roles = array_reverse( get_editable_roles() );

		$formatted_roles = array();

		foreach ( $editable_roles as $role => $details ) {
				$name                     = translate_user_role( $details['name'] );
				$formatted_roles[ $role ] = $name;
		}

		return $formatted_roles;
	}
}

// instantiate the class
new ncr_options_panel();
