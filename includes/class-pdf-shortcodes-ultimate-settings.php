<?php
/**
 * PDF Shortcodes Ultimate Settings class.
 *
 * @package PDF Shortcodes Ultimate
 */

defined( 'ABSPATH' ) || exit;

/**
 * PDF Shortcodes Ultimate Settings class.
 */
class PDF_Shortcodes_Ultimate_Settings {

	/**
	 * The single instance of PDF_Shortcodes_Ultimate_Settings.
	 *
	 * @var    object
	 * @access private
	 * @since  1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var    array
	 * @access public
	 * @since  1.0.0
	 */
	public $settings = array();

	/**
	 * Constructor function for plugin settings.
	 *
	 * @param object $parent Parent object.
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;

		$this->base = 'wpt_';

		// Initialise settings.
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings.
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu.
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page.
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 *
	 * @return void
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 *
	 * @return void
	 */
	public function add_menu_item() {
		$page = add_options_page( __( 'Plugin Settings', 'pdf-shortcodes-ultimate' ) , __( 'Plugin Settings', 'pdf-shortcodes-ultimate' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 *
	 * @return void
	 */
	public function settings_assets() {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below.
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );

		// We're including the WP media scripts here because they're needed for the image upload field
		// If you're not including an image upload then you can leave this function call out.
		wp_enqueue_media();

		wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
		wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 *
	 * @param  array $links Existing links.
	 * @return array        Modified links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'pdf-shortcodes-ultimate' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page.
	 */
	private function settings_fields() {

		$settings['standard'] = array(
			'title'                 => __( 'Standard', 'pdf-shortcodes-ultimate' ),
			'description'           => __( 'These are fairly standard form input fields.', 'pdf-shortcodes-ultimate' ),
			'fields'                => array(
				array(
					'id'            => 'text_field',
					'label'         => __( 'Some Text' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This is a standard text field.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'text',
					'default'       => '',
					'placeholder'   => __( 'Placeholder text', 'pdf-shortcodes-ultimate' )
				),
				array(
					'id'            => 'password_field',
					'label'         => __( 'A Password' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This is a standard password field.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'password',
					'default'       => '',
					'placeholder'   => __( 'Placeholder text', 'pdf-shortcodes-ultimate' )
				),
				array(
					'id'            => 'secret_text_field',
					'label'         => __( 'Some Secret Text' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This is a secret text field - any data saved here will not be displayed after the page has reloaded, but it will be saved.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'text_secret',
					'default'       => '',
					'placeholder'   => __( 'Placeholder text', 'pdf-shortcodes-ultimate' )
				),
				array(
					'id'            => 'text_block',
					'label'         => __( 'A Text Block' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This is a standard text area.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'textarea',
					'default'       => '',
					'placeholder'   => __( 'Placeholder text for this textarea', 'pdf-shortcodes-ultimate' )
				),
				array(
					'id'            => 'single_checkbox',
					'label'         => __( 'An Option', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'A standard checkbox - if you save this option as checked then it will store the option as \'on\', otherwise it will be an empty string.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'checkbox',
					'default'       => '',
				),
				array(
					'id'            => 'select_box',
					'label'         => __( 'A Select Box', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'A standard select box.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'select',
					'options'       => array( 'drupal' => 'Drupal', 'joomla' => 'Joomla', 'wordpress' => 'WordPress' ),
					'default'       => 'wordpress',
				),
				array(
					'id'            => 'radio_buttons',
					'label'         => __( 'Some Options', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'A standard set of radio buttons.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'radio',
					'options'       => array( 'superman' => 'Superman', 'batman' => 'Batman', 'ironman' => 'Iron Man' ),
					'default'       => 'batman',
				),
				array(
					'id'            => 'multiple_checkboxes',
					'label'         => __( 'Some Items', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'You can select multiple items and they will be stored as an array.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'checkbox_multi',
					'options'       => array(
						'square'    => 'Square',
						'circle'    => 'Circle',
						'rectangle' => 'Rectangle',
						'triangle'  => 'Triangle',
					),
					'default'       => array( 'circle', 'triangle' ),
				),
			),
		);

		$settings['extra'] = array(
			'title'                 => __( 'Extra', 'pdf-shortcodes-ultimate' ),
			'description'           => __( 'These are some extra input fields that maybe aren\'t as common as the others.', 'pdf-shortcodes-ultimate' ),
			'fields'                => array(
				array(
					'id'            => 'number_field',
					'label'         => __( 'A Number' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This is a standard number field - if this field contains anything other than numbers then the form will not be submitted.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'number',
					'default'       => '',
					'placeholder'   => __( '42', 'pdf-shortcodes-ultimate' )
				),
				array(
					'id'            => 'colour_picker',
					'label'         => __( 'Pick a colour', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This uses WordPress\' built-in colour picker - the option is stored as the colour\'s hex code.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'color',
					'default'       => '#21759B',
				),
				array(
					'id'            => 'an_image',
					'label'         => __( 'An Image' , 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an imge the thumbnail will display above these buttons.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'image',
					'default'       => '',
					'placeholder'   => '',
				),
				array(
					'id'            => 'multi_select_box',
					'label'         => __( 'A Multi-Select Box', 'pdf-shortcodes-ultimate' ),
					'description'   => __( 'A standard multi-select box - the saved data is stored as an array.', 'pdf-shortcodes-ultimate' ),
					'type'          => 'select_multi',
					'options'       => array(
						'linux'     => 'Linux',
						'mac'       => 'Mac',
						'windows'   => 'Windows',
					),
					'default'       => array( 'linux' ),
				),
			),
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab.
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section
					&& $current_section != $section ) {
					continue;
				}

				// Add section to page.
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field.
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field.
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page.
					add_settings_field(
						$field['id'],
						$field['label'],
						array( $this->parent->admin, 'display_field' ),
						$this->parent->_token . '_settings',
						$section,
						array(
							'field' => $field,
							'prefix' => $this->base,
						)
					);
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
	}

	/**
	 * Settings section
	 *
	 * @param  array $section Section info.
	 *
	 * @return void
	 */
	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 *
	 * @return void
	 */
	public function settings_page() {

		// Build page HTML.
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2>' . __( 'Plugin Settings' , 'pdf-shortcodes-ultimate' ) . '</h2>' . "\n";

		$tab = '';
		if ( isset( $_GET['tab'] ) &&
			$_GET['tab'] ) {
			$tab .= $_GET['tab'];
		}

		// Show page tabs.
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";

			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class.
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 == $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link.
				$tab_link = add_query_arg(
					array(
						'tab' => $section,
					)
				);

				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab.
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++$c;
			}

			$html .= '</h2>' . "\n";
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

			// Get settings fields.
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			do_settings_sections( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();

			$html .= '<p class="submit">' . "\n";
				$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
				$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'pdf-shortcodes-ultimate' ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main PDF_Shortcodes_Ultimate_Settings Instance
	 *
	 * Ensures only one instance of PDF_Shortcodes_Ultimate_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see PDF_Shortcodes_Ultimate()
	 *
	 * @param object $parent Parent object.
	 * @return Main PDF_Shortcodes_Ultimate_Settings instance
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()
}
